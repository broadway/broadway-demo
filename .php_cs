<?php

$config = require 'vendor/broadway/coding-standard/.php_cs.dist';

$config->setFinder(
    \PhpCsFixer\Finder::create()
        ->in([
            getcwd() . '/src',
            getcwd() . '/test',
            getcwd() . '/bin',
            getcwd() . '/public',
            getcwd() . '/config',
        ])
);

return $config;
