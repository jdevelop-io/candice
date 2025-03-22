<?php

use Candice\Shared\Infrastructure\Symfony\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    if (!is_string($context['APP_ENV'])) {
        throw new \RuntimeException('APP_ENV environment variable is not set.');
    }

    return new Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
};
