<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 28.10.18
 * Time: 15:42
 */

namespace Pluswerk\FluidStyleguide\Model;

use Gajus\Dindent\Indenter;
use Mihaeu\HtmlFormatter;
use Pluswerk\FluidStyleguide\Configuration\StyleguideConfiguration;
use Pluswerk\FluidStyleguide\Registry\SectionGroupRegistry;
use Symfony\Component\Finder\SplFileInfo;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\View\StandaloneView;

class Section
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $html;

    /**
     * @var SplFileInfo
     */
    private $file;

    /**
     * @var string
     */
    private $relativePath;

    /**
     * @var StyleguideConfiguration
     */
    private $styleguideConfiguration;

    /**
     * @var StandaloneView
     */
    private $standaloneView;

    /**
     * @var array
     */
    private $dummyData;

    /**
     * @var Indenter
     */
    private $indenter;

    /**
     * @var SectionGroup
     */
    private $sectionGroup;

    /**
     * Section constructor.
     *
     * @param string $headline
     * @param string $description
     * @param string $html
     */
    public function __construct(\SplFileInfo $file, ObjectManager $objectManager = null)
    {
        $this->file  = $file;
        $this->title = $file->getBasename('.html');
        /** @var ObjectManager $objectManager */
        $objectManager = $objectManager ?? GeneralUtility::makeInstance(ObjectManager::class);
        $this->styleguideConfiguration = $objectManager->get(StyleguideConfiguration::class);
        $this->standaloneView = $objectManager->get(StandaloneView::class);
        $this->indenter = $objectManager->get(Indenter::class);
        $jsonFilePathname = $file->getPath() . '/' . $file->getBasename('.html') . '.json';
        $jsonFile = file_get_contents($jsonFilePathname);
        $jsonFileContent = \json_decode($jsonFile, true);
        $this->dummyData = $jsonFileContent['data'];

        /** @var SectionGroupRegistry $sectionGroupRegistry */
        $sectionGroupRegistry = $objectManager->get(SectionGroupRegistry::class);
        $groupIdentifier = $this->retrieveSectionGroupIdentifier();
        $sectionGroupRegistry->addSection($this, $groupIdentifier);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        if ($this->relativePath === null) {
            $this->relativePath = '';
            foreach ($this->styleguideConfiguration->getAllBasePaths() as $basePath) {
                $basePath = str_replace('/', '\/', $basePath);
                if (preg_match('/' . $basePath . '(.*)/', $this->file->getPath(), $hits)) {
                    $this->relativePath = trim($hits[1], '/');
                }
            }
        }
        return $this->relativePath;
    }

    /**
     * @param bool $removeOwnDir
     *
     * @return array
     */
    public function getRelativePathArray(bool $removeOwnDir = false): array
    {
        $pathArray = explode('/', $this->getRelativePath());
        if ($removeOwnDir) {
            array_pop($pathArray);
        }
        return $pathArray;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        $relativePathArray = $this->getRelativePathArray();
        return implode('__', $relativePathArray);
    }

    /**
     * @param SectionGroup $sectionGroup
     */
    public function setSectionGroup(SectionGroup $sectionGroup): void
    {
        $this->sectionGroup = $sectionGroup;
    }

    /**
     * @return SectionGroup
     */
    public function getSectionGroup(): SectionGroup
    {
        return $this->sectionGroup;
    }

    /**
     * @return string
     */
    public function retrieveSectionGroupIdentifier(): string
    {
        $relativePathArray = $this->getRelativePathArray(true);
        return implode('__', $relativePathArray);
    }

    /**
     * @return string
     * @throws \Gajus\Dindent\Exception\RuntimeException
     */
    public function getHtml(): string
    {
        if ($this->html === null)
        {
            $this->html = '';
            foreach ($this->dummyData as $dummyDatum) {
                $this->standaloneView->setTemplatePathAndFilename($this->file->getPathname());
                $this->standaloneView->assignMultiple($dummyDatum);
                $this->html .= trim($this->formatHtml($this->standaloneView->render()));
            }
            if ($this->html) {
                $this->html = $this->indenter->indent($this->html);
            }
        }
        return $this->html;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    private function formatHtml(string $html): string
    {
        return $html;
    }
}