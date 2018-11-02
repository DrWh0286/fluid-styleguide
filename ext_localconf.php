<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 27.10.18
 * Time: 09:33
 */

/***************
 * Living Styleguide: configure plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Pluswerk.FluidStyleguide',
    'Styleguide',
    [
        'Styleguide' => 'index',
    ],
    // non-cacheable actions
    [
        'Styleguide' => 'index',
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] =
    \Pluswerk\Styleguide\Command\PatternlabCommand::class;
