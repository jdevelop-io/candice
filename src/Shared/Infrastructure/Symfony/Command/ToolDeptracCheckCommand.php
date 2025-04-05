<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Symfony\Command;

use DirectoryIterator;
use Generator;
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
class ToolDeptracCheckCommand extends Command
{
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Deptrac Configuration Checker');

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

    private function checkContext(array $context, InputInterface $input, SymfonyStyle $io): bool
    {
        $contextName = $context['name'];
        $contextPath = $context['path'];
        $baselineFileName = $input->getOption('baseline-file');

        $io->text("Checking context <info>$contextName</info> in <comment>$contextPath</comment>");
        $baselineFile = "$contextPath/$baselineFileName";
        if (!file_exists($baselineFile)) {
            $io->text(sprintf('  <error>Baseline file %s not found</error>', $baselineFile));
            return false;
        }

        $yaml = Yaml::parseFile($baselineFile);
        if (!isset($yaml['deptrac']) || !isset($yaml['deptrac']['paths'])) {
            $io->text("  <error>The deptrac.paths key is missing in the baseline file</error>");
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
                sprintf(
                    "  <error>The deptrac.paths[0] key should be %s or ./%s. Found: %s</error>",
                    $contextPath,
                    $contextPath,
                    $path
                )
            );
            return false;
        }

        $io->text("  <info>Baseline file is valid</info>");

        return true;
    }

    private function checkConfigFile(InputInterface $input, SymfonyStyle $io): bool
    {
        $contexts = $this->getContexts($input->getOption('base-dir'));
        $baselineFile = $input->getOption('baseline-file');
        $contextsBaselineFiles = array_map(
            static fn(array $context) => "./$context[path]/$baselineFile",
            iterator_to_array($contexts)
        );
        $configFile = $input->getOption('config');
        $yaml = Yaml::parseFile($configFile);
        if (!isset($yaml['imports'])) {
            $io->text("  <error>The imports key is missing in the Deptrac \"$configFile\" configuration file</error>");
            return false;
        }

        $imports = $yaml['imports'];
        if (!is_array($imports)) {
            $io->text(
                "  <error>The imports key is not an array in the Deptrac \"$configFile\" configuration file</error>"
            );
            return false;
        }

        foreach ($imports as $index => $import) {
            if (!is_string($import)) {
                $io->text(
                    sprintf(
                        "  <error>The imports[%d] key is not a string in the Deptrac \"%s\" configuration file</error>",
                        $index,
                        $configFile
                    )
                );
                return false;
            }

            if (!in_array($import, $contextsBaselineFiles, true)) {
                $io->text(
                    sprintf(
                        "  <error>The imports[%d] key should be one of the following: %s</error>",
                        $index,
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

        $io->text("  <info>Deptrac configuration file is valid</info>");

        return true;
    }
}
