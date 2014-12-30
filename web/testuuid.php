<?php

require __DIR__ . '/../vendor/autoload.php';

try {
var_dump(Assert\Assertion::uuid('89334a7d-cf37-4c5b-acf1-0f39a19c5547'));
} catch (\Exception $e) {
var_dump($e);
}
