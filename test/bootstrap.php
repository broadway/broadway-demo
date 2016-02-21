<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (file_exists($file = __DIR__.'/../vendor/autoload.php')) {
    $loader = require $file;
    $loader->add('BroadwayDemo', __DIR__);
    $loader->add('Broadway', __DIR__ . '/../vendor/qqq/broadway/test');
} else {
    throw new RuntimeException('Install dependencies to run test suite.');
}
