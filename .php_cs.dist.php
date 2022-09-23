<?php

$rules = [
    '@Symfony'                     => true,
    'binary_operator_spaces'       => [
        'operators' => [
            '=>' => 'align',
        ],
    ],
    'declare_strict_types'         => true,
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setUsingCache(true);
