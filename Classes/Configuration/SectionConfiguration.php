<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 02.11.18
 * Time: 18:23
 */

namespace Pluswerk\FluidStyleguide\Configuration;


class SectionConfiguration
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @var string
     */
    private $configurationFilePathname;

    /**
     * SectionConfiguration constructor.
     *
     * @param array $configuration
     */
    public function __construct(string $configurationFilePathname)
    {
        $this->configuration = [];
        $this->configurationFilePathname = $configurationFilePathname;
    }

    /**
     * @return array
     */
    public function getSectionDummyData(): array
    {
        $conf = $this->getConfiguration();
        if (isset($conf['data']) && \is_array($conf['data'])) {
            return $conf['data'];
        }
        return [];
    }

    /**
     * @return bool
     */
    public function renderCodeBlock(): bool
    {
        $conf = $this->getConfiguration();
        if (isset($conf['renderCodeBlock'])) {
            return (bool)$conf['renderCodeBlock'];
        }
        return true;
    }

    /**
     * @return array
     */
    private function getConfiguration(): array
    {
        if (empty($this->configuration)
            && file_exists($this->configurationFilePathname)
            && is_readable($this->configurationFilePathname)
        ) {
            $fileContent = file_get_contents($this->configurationFilePathname);
            $this->configuration = \json_decode($fileContent, true);
        }
        return $this->configuration;
    }
}