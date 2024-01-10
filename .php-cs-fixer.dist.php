<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
    ]);

$config = new PhpCsFixer\Config();

return (new Config())
    ->setFinder($finder)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PSR12' => true,
        'yoda_style' => false,
    ]);
