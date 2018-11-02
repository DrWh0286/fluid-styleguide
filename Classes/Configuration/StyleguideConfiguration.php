<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 28.10.18
 * Time: 16:58
 */

namespace Pluswerk\FluidStyleguide\Configuration;

use TYPO3\CMS\Core\SingletonInterface;

class StyleguideConfiguration implements SingletonInterface
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @return array
     */
    public function getAllBasePaths(): array
    {
        $conf = $this->getConfiguration();
        return $conf['styleguideBasePaths'];
    }

    /**
     * @return array
     */
    private function getConfiguration(): array
    {
        if ($this->configuration === null) {
            $fluidStyleguideExtConf = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['fluid_styleguide'];
            $this->configuration['registeredExtensions']
                = array_keys($fluidStyleguideExtConf['registeredExtensions']);
            $this->configuration['styleguideBasePaths'] = array_column(
                $fluidStyleguideExtConf['registeredExtensions'],
                'styleguideBasePath'
            );
        }

        return $this->configuration;
    }
}