<?php

$config = require 'vendor/broadway/coding-standard/.php-cs-fixer.dist.php';

$config->setFinder(
    \PhpCsFixer\Finder::create()
        ->in([
            __DIR__ . '/src',
            __DIR__ . '/test',
            __DIR__ . '/bin',
            __DIR__ . '/config',
            __DIR__ . '/public',
        ])
);

return $config;
