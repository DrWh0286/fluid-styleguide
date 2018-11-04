<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 28.10.18
 * Time: 12:55
 */

namespace Pluswerk\FluidStyleguide\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class StyleguideManagementUtility
{
    private static $styleguideNamespaces = [
        'at' => 'Atoms',
        'mo' => 'Molecules',
        'or' => 'Organisms',
        'te' => 'Templates',
        'pa' => 'Pages'
    ];

    /**
     * @param $extKey
     * @param $vendor
     * @param string $baseStyleguidePath
     *
     * @return void
     */
    public static function registerForStyleguide(
        $extKey,
        $vendor,
        $baseStyleguidePath = 'Resources/Private/Styleguide/'
    ): void {
        $baseNamespace = trim($vendor, '\\') . '\\' . GeneralUtility::underscoredToUpperCamelCase($extKey) . '\\';
        $baseStyleguidePath = trim($baseStyleguidePath, '/') . '/';
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_styleguide']['registeredExtensions'][$extKey] = [
            'styleguideBasePath' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
                $extKey,
                $baseStyleguidePath
            )
        ];
        $fluidComponentsExtConfNamespaces = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_components']['namespaces']
                                            ?? [];
        $fluidNamespaces = $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces'] ?? [];

        foreach (self::$styleguideNamespaces as $short => $styleguideNamespace) {
            $fluidComponentsExtConfNamespaces[$baseNamespace . $styleguideNamespace] =
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
                    $extKey,
                    $baseStyleguidePath . $styleguideNamespace
                );
            if (!isset($fluidNamespaces[$short])) {
                $fluidNamespaces[$short] = [];
            }
            $fluidNamespaces[$short][] = $baseNamespace . $styleguideNamespace;
        }

        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_components']['namespaces'] = $fluidComponentsExtConfNamespaces;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces'] = $fluidNamespaces;
    }

    /**
     * @param string $viewhelperShort
     *
     * @return string
     */
    public static function getNamespacePartFromViewHelperShort(string $viewhelperShort): string
    {
        return self::$styleguideNamespaces[$viewhelperShort] ?? '';
    }
}
