<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        'braces' => [
            //'position_after_anonymous_constructs' => 'next',
            'position_after_control_structures' => 'next',
        ],
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'multiline_whitespace_before_semicolons' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_unused_imports' => false,
        'yoda_style' => false,
        'no_unreachable_default_argument_value' => false,
        'return_assignment' => false,
    ])
    ->setLineEnding("\n");
