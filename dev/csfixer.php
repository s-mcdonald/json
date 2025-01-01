<?php

declare(strict_types=1);

return (new PhpCsFixer\Config())
        ->setRules([
            '@Symfony' => true,
            '@PHP82Migration' => true,
            '@PHPUnit100Migration:risky' => true,

//            'dir_constant ' => true,
            'modernize_strpos' => true,
            'mb_str_functions' => true,
            'ordered_imports' => true,
            'ordered_attributes' => true,
            'fully_qualified_strict_types' => true,
            'no_unneeded_import_alias' => true,
            'global_namespace_import' => true,
//            'group_import' => true,
            'native_function_invocation' => false,

            'heredoc_indentation' => false,
            'class_definition' => [
                'inline_constructor_arguments' => false,
            ],
            'yoda_style' => true,
            'concat_space' => ['spacing' => 'one'],
            'trailing_comma_in_multiline' => [
                'after_heredoc' => true,
                'elements' => ['arguments', 'arrays', 'match', 'parameters'],
            ],
            'nullable_type_declaration' => ['syntax' => 'union'],
            'phpdoc_align' => ['align' => 'left'],
            'single_line_throw' => false,

            // PHPUnit
            'php_unit_data_provider_return_type' => true,
            'declare_strict_types' => true,
            'ordered_class_elements' => true,
        ])
        ->setFinder((new PhpCsFixer\Finder())
            ->in(__DIR__ . '/../src'))
        ->setUsingCache(true)
        ->setRiskyAllowed(true)
        ->setCacheFile(__DIR__ . '/dev/.cache/cs-fixer.cache')
;
