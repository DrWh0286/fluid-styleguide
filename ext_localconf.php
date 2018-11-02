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
        'Styleguide' => '',
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] =
    \Pluswerk\Styleguide\Command\PatternlabCommand::class;

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_components']['namespaces']['Pluswerk\\FluidStyleguide\\Components'] =
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
        'fluid_styleguide',
        'Resources/Private/Components'
    );


// Make fc a global namespace
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['sg'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['sg'] = [];
}
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['sg'][] = 'Pluswerk\\FluidStyleguide\\Components';
