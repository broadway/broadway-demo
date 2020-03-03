<?php

$config = require 'vendor/broadway/coding-standard/.php_cs.dist';

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
