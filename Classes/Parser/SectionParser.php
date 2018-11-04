<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 28.10.18
 * Time: 16:56
 */

namespace Pluswerk\FluidStyleguide\Parser;

use Pluswerk\FluidStyleguide\Configuration\StyleguideConfiguration;
use Pluswerk\FluidStyleguide\Model\Section;
use Pluswerk\FluidStyleguide\Registry\SectionGroupRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class SectionParser
{
    /**
     * @var StyleguideConfiguration
     */
    private $styleguideConfiguration;

    /**
     * @var SectionGroupRegistry
     */
    private $sectionGroupRegistry;

    /**
     * SectionParser constructor.
     *
     * @param ObjectManager|null $objectManager
     */
    public function __construct(ObjectManager $objectManager = null)
    {
        $objectManager = $objectManager ?? GeneralUtility::makeInstance(ObjectManager::class);
        $this->styleguideConfiguration = $objectManager->get(StyleguideConfiguration::class);
        $this->sectionGroupRegistry = $objectManager->get(SectionGroupRegistry::class);
    }

    public function parse()
    {
        $pathObjects = [];
        foreach ($this->styleguideConfiguration->getAllBasePaths() as $basePath) {
            $pathObject = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($basePath),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            $pathObject->setFlags(\FilesystemIterator::SKIP_DOTS);
            $pathObjects[$basePath] = $pathObject;
        }

        foreach ($pathObjects as $objects) {
            $objects = iterator_to_array($objects);
            /** @var \SplFileInfo $object */
            foreach ($objects as $object) {
                if ($object->isFile() && $object->getExtension() === 'html') {
                    new Section($object);
                }
            }
        }
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return array
     */
    public function getUsedComponentViewHelperStrings(\SplFileInfo $file): array
    {
        if (file_exists($file)) {
            $content = file_get_contents($file->getPathname());
            if (preg_match_all('/<((at|mo|or|te|pa):[a-zA-Z\.]*) ?/', $content, $hits)) {
                return $hits[1];
            }
        }
        return [];
    }
}
