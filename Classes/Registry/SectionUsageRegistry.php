<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 03.11.18
 * Time: 11:06
 */

namespace Pluswerk\FluidStyleguide\Registry;


use Pluswerk\FluidStyleguide\Model\Section;
use Pluswerk\FluidStyleguide\Utility\StyleguideManagementUtility;
use TYPO3\CMS\Core\SingletonInterface;

class SectionUsageRegistry implements SingletonInterface
{
    /**
     * @var array
     */
    private $sectionUsages;

    /**
     * @param Section $section
     * @param string $usedViewHelperString
     */
    public function addSectionUsage(Section $section, string $usedViewHelperString): void
    {
        $this->sectionUsages[$this->convertVewHelperStringToSectionPath($usedViewHelperString)][] = $section;
    }

    /**
     * @param string $section
     * @param array $usedViewHelperStrings
     */
    public function addSectionsUsage(Section $section, array $usedViewHelperStrings): void
    {
        foreach ($usedViewHelperStrings as $usedViewHelperString) {
            $this->addSectionUsage($section, $usedViewHelperString);
        }
    }

    /**
     * @param string $sectionPath
     *
     * @return array
     */
    public function getUsagesOfSection(string $sectionPath): array
    {
        return $this->sectionUsages[$sectionPath] ?? [];
    }

    /**
     * @param string $vewHelperString
     *
     * @return string
     */
    private function convertVewHelperStringToSectionPath(string $vewHelperString): string
    {
        $viewHelper = explode(':', $vewHelperString);
        $path = StyleguideManagementUtility::getNamespacePartFromViewHelperShort($viewHelper[0]) . '__';
        $viewHelper = explode('.', $viewHelper[1]);
        array_walk($viewHelper, function (&$item) {
            $item = \ucfirst($item);
        });
        $path .= implode('__', $viewHelper);
        return $path;
    }
}