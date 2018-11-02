<?php

/**
 * Extension Manager/Repository config file for ext "tub_base_package".
 *
 * This extension is based on Higher Education Package (t3g/university-package)
 * of the Higher Education Package Team of TYPO3 GmbH. For more information contact info@typo3.com.
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Fluid Styleguide',
    'description' => 'Provides a css and fluid component based styleguide',
    'category' => 'template',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
            'fluid_components' => '*'
        ],
    ],
    'state' => 'alpha',
    'uploadfolder' => 0,
    'createDirs' => 'typo3temp/assets/fluid_styleguide/styleguide',
    'clearCacheOnLoad' => 1,
    'author' => 'Sebastian Hofer',
    'author_email' => 'sebastian.hofer@pluswerk.ag',
    'author_company' => 'Pluswerk AG',
    'version' => '0.0.1',
];
