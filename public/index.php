<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) 2020 Broadway project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use BroadwayDemo\AppKernel;
use Qandidate\Stack\RequestId;
use Qandidate\Stack\UuidRequestIdGenerator;
use Symfony\Component\HttpFoundation\Request;

$loader = require __DIR__.'/../vendor/autoload.php';

$kernel = new AppKernel('dev', true);

// Stack it!
$generator = new UuidRequestIdGenerator(42);
$requestId = new RequestId($kernel, $generator);

$request = Request::createFromGlobals();
$response = $requestId->handle($request);
$response->send();
$kernel->terminate($request, $response);
