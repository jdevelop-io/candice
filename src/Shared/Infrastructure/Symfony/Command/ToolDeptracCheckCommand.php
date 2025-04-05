<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Symfony\Command;

use DirectoryIterator;
use Generator;
use Override;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'tool:deptrac:check',
    description: 'Check the Deptrac configuration',
)]
final class ToolDeptracCheckCommand extends Command
{
    #[Override]
    protected function configure(): void
    {
        $this->setHelp('This command allows you to check the Deptrac configuration.');
        $this->addOption(
            'config',
            null,
            InputOption::VALUE_OPTIONAL,
            'Path to the Deptrac configuration file',
            'deptrac.yaml'
        );
        $this->addOption(
            'baseline-file',
            null,
            InputOption::VALUE_OPTIONAL,
            'Path to the Deptrac baseline file inside each bounded context',
            'deptrac.baseline.yaml'
        );
        $this->addOption(
            'base-dir',
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Base directory where the Deptrac configuration file should be located',
            ['src', 'tests/Unit']
        );
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Deptrac Configuration Checker');

        /** @var string[] $baseDirs */
        $baseDirs = $input->getOption('base-dir');

        $errors = false;

        foreach ($io->progressIterate($this->getContexts($baseDirs)) as $context) {
            $io->writeln('');
            if (!$this->checkContext($context, $input, $io)) {
                $errors = true;
            }
            $io->writeln('');
        }

        if (!$this->checkConfigFile($input, $io)) {
            $errors = true;
        }

        return $errors ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * @param string[] $baseDirs
     * @psalm-return Generator<int, array{name: string, path: string}, mixed, void>
     */
    private function getContexts(array $baseDirs): Generator
    {
        foreach ($baseDirs as $baseDir) {
            if (!is_dir($baseDir)) {
                continue;
            }

            foreach (new DirectoryIterator($baseDir) as $dir) {
                if ($dir->isDot() || !$dir->isDir()) {
                    continue;
                }

                $contextName = $dir->getFilename();
                $contextDir = $dir->getPathname();

                yield [
                    'name' => $contextName,
                    'path' => $contextDir,
                ];
            }
        }
    }

    /**
     * @param array{name: string, path: string} $context
     */
    private function checkContext(array $context, InputInterface $input, SymfonyStyle $io): bool
    {
        $contextName = $context['name'];
        $contextPath = $context['path'];

        $baselineFileName = $this->getBaselineFileOption($input);

        $io->text("Checking context <info>$contextName</info> in <comment>$contextPath</comment>");
        $baselineFile = "$contextPath/$baselineFileName";
        if (!file_exists($baselineFile)) {
            $io->text("  <error>Baseline file $baselineFile not found</error>");
            return false;
        }

        $yaml = $this->parseYamlFile($baselineFile);
        return $this->checkContextBaselineFile($yaml, $contextPath, $io);
    }

    /**
     * @param array<string, mixed> $yaml
     */
    private function checkContextBaselineFile(array $yaml, string $contextPath, SymfonyStyle $io): bool
    {
        if (!isset($yaml['deptrac'])) {
            $io->text("  <error>The deptrac key is missing</error>");
            return false;
        }

        if (!is_array($yaml['deptrac'])) {
            $io->text("  <error>The deptrac key is not an array</error>");
            return false;
        }

        $paths = $yaml['deptrac']['paths'];
        if (!is_array($paths)) {
            $io->text("  <error>The deptrac.paths key is not an array</error>");
            return false;
        }

        if (count($paths) !== 1) {
            $io->text(
                "  <error>Only one path is allowed in the deptrac.paths key. Found: " . count($paths) . "</error>"
            );
            return false;
        }

        $path = $paths[0];
        if (!is_string($path)) {
            $io->text("  <error>The deptrac.paths[0] key is not a string. Found: " . gettype($path) . "</error>");
            return false;
        }

        if (!in_array($path, [$contextPath, "./$contextPath"], true)) {
            $io->text(
                "  <error>The deptrac.paths[0] key should be $contextPath or ./$contextPath. Found: $path</error>",
            );
            return false;
        }

        $io->text("  <info>Baseline file is valid</info>");

        return true;
    }

    private function checkConfigFile(InputInterface $input, SymfonyStyle $io): bool
    {
        $baseDirs = $this->getBaseDirOption($input);
        $baselineFile = $this->getBaselineFileOption($input);
        $configFile = $this->getConfigFileOption($input);

        $contexts = $this->getContexts($baseDirs);
        $contextsBaselineFiles = array_map(
            static fn(array $context) => "./$context[path]/$baselineFile",
            iterator_to_array($contexts)
        );

        $yaml = $this->parseYamlFile($configFile);
        return $this->validateConfigImports($yaml, $contextsBaselineFiles, $io);
    }

    /**
     * @param array<string, mixed> $yaml
     * @param string[] $contextsBaselineFiles
     */
    private function validateConfigImports(array $yaml, array $contextsBaselineFiles, SymfonyStyle $io): bool
    {
        if (!isset($yaml['imports'])) {
            $io->text("  <error>The imports key is missing</error>");
            return false;
        }

        $imports = $yaml['imports'];
        if (!is_array($imports)) {
            $io->text(
                "  <error>The imports key is not an array</error>"
            );
            return false;
        }

        foreach ($imports as $index => $import) {
            if (!is_string($import)) {
                $io->text("  <error>The imports[$index] key is not a string</error>");
                return false;
            }

            if (!in_array($import, $contextsBaselineFiles, true)) {
                $io->text(
                    sprintf(
                        "  <error>The imports[$index] key should be one of the following: %s</error>",
                        implode(', ', $contextsBaselineFiles),
                    )
                );
                $io->text(
                    "  <error>" . str_pad(
                        "Found",
                        strlen("The imports[$index] key should be one of the following")
                    ) . ": $import</error>",
                );
                return false;
            }
        }

        $missingImports = array_diff($contextsBaselineFiles, $imports);
        if (!empty($missingImports)) {
            $io->text("  <error>Missing imports:</error>");
            foreach ($missingImports as $missingImport) {
                $io->text("  <error> - $missingImport</error>");
            }
            return false;
        }

        $io->text("  <info>Deptrac configuration file is valid</info>");

        return true;
    }

    private function getBaselineFileOption(InputInterface $input): string
    {
        /** @var string $baselineFile */
        $baselineFile = $input->getOption('baseline-file');

        return $baselineFile;
    }

    /**
     * @return array<string, mixed>
     */
    private function parseYamlFile(string $filePath): array
    {
        $yaml = Yaml::parseFile($filePath);

        if (!is_array($yaml)) {
            throw new RuntimeException("Failed to parse YAML file: $filePath");
        }

        /** @var array<string, mixed> $yaml */
        return $yaml;
    }

    /**
     * @return string[]
     */
    private function getBaseDirOption(InputInterface $input): array
    {
        /** @var string[] $baseDirs */
        $baseDirs = $input->getOption('base-dir');

        return $baseDirs;
    }

    private function getConfigFileOption(InputInterface $input): string
    {
        /** @var string $config */
        $config = $input->getOption('config');

        return $config;
    }
}
