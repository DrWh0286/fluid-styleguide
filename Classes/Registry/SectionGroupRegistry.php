<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 28.10.18
 * Time: 19:27
 */

namespace Pluswerk\FluidStyleguide\Registry;

use Pluswerk\FluidStyleguide\Model\Section;
use Pluswerk\FluidStyleguide\Model\SectionGroup;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class SectionGroupRegistry implements SingletonInterface
{
    /**
     * @var SectionGroup[]
     */
    private $sectionGroups;

    /**
     * @var Section[]
     */
    private $sections;

    /**
     * @param Section $section
     */
    public function addSection(Section $section): void
    {
        $this->sections[$section->getIdentifier()] = $section;
        $sectionGroup = $this->getSectionGroup(
            $section->retrieveSectionGroupIdentifier(),
            $section->getRelativePathArray(true)
        );
        $sectionGroup->addSection($section);
        $section->setSectionGroup($sectionGroup);
    }

    /**
     * @param SectionGroup $sectionGroup
     */
    public function addSectionGroup(SectionGroup $sectionGroup)
    {
        $this->sectionGroups[$sectionGroup->getIdentifier()] = $sectionGroup;
        if (!empty($sectionGroup->getPath())) {
            new SectionGroup($sectionGroup->getPath());
            $path = $sectionGroup->getPath();
            $this->getSectionGroup(end($path))->addSectionGroup($sectionGroup);
        }
    }

    /**
     * @param SectionGroup $sectionGroupToAdd
     * @param string $groupIdentifier
     */
    public function addGroupToGroup(SectionGroup $sectionGroupToAdd, string $groupIdentifier): void
    {
        $sectionGroup = $this->getSectionGroup($groupIdentifier);
        $sectionGroup->addSectionGroup($sectionGroupToAdd);
        $this->sectionGroups[$groupIdentifier] = $sectionGroup;
    }

    /**
     * @return SectionGroup[]
     */
    public function getRootSectionGroups(): array
    {
        $rootSectionGroups = [];
        foreach ($this->sectionGroups as $sectionGroup) {
            if ($sectionGroup->isRootSectionGroup()) {
                $rootSectionGroups[$sectionGroup->getIdentifier()] = $sectionGroup;
            }
        }
        ksort($rootSectionGroups);
        return $rootSectionGroups;
    }

    /**
     * @param string $groupIdentifier
     * @param array $groupPath
     *
     * @return SectionGroup
     */
    private function getSectionGroup(string $groupIdentifier, array $groupPath = [])
    {
        if (!isset($this->sectionGroups[$groupIdentifier])) {
            new SectionGroup($groupPath);
        }
        return $this->sectionGroups[$groupIdentifier];
    }

    /**
     * @return Section[]
     */
    public function getSections(): array
    {
        ksort($this->sections);
        return $this->sections;
    }

    /**
     * @param string $identifier
     * @param array $sections
     *
     * @return array
     */
    public function getSectionsByGroupIdentifier(string $identifier, array $sections = []): array
    {
        $sections = array_merge($sections, $this->sectionGroups[$identifier]->getSections()) ;
        $sectionGroups = $this->sectionGroups[$identifier]->getSectionGroups();
        if (!empty($sectionGroups)) {
            /** @var SectionGroup $sectionGroup */
            foreach ($sectionGroups as $sectionGroup) {
                $sections = $this->getSectionsByGroupIdentifier($sectionGroup->getIdentifier(), $sections);
            }
        }
        ksort($sections);
        return $sections;
    }

    /**
     * @param string $identifier
     *
     * @return Section
     */
    public function getSectionBySectionIdentifier(string $identifier): Section
    {
        return $this->sections[$identifier];
    }
}
