<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 27.10.18
 * Time: 09:34
 */

namespace Pluswerk\FluidStyleguide\Controller;

use Pluswerk\FluidStyleguide\Model\Section;
use Pluswerk\FluidStyleguide\Parser\SectionParser;
use Pluswerk\FluidStyleguide\Registry\SectionGroupRegistry;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class StyleguideController extends ActionController
{
    /**
     * index action
     */
    public function indexAction()
    {
        $section = '';
        $sectionGroup = '';
        if ($this->request->hasArgument('section')) {
            $section = $this->request->getArgument('section');
        }
        if ($this->request->hasArgument('sectionGroup')) {
            $sectionGroup = $this->request->getArgument('sectionGroup');
        }
        /** @var SectionParser $sectionParser */
        $sectionParser = $this->objectManager->get(SectionParser::class);
        $sectionParser->parse();
        /** @var SectionGroupRegistry $sectionGroupRegistry */
        $sectionGroupRegistry = $this->objectManager->get(SectionGroupRegistry::class);

        if ($section !== '') {
            $sections = $sectionGroupRegistry->getSectionBySectionIdentifier($section);
        } elseif ($sectionGroup !== '') {
            $sections = $sectionGroupRegistry->getSectionsByGroupIdentifier($sectionGroup);
        } else {
            $sections = $sectionGroupRegistry->getSections();
        }

        if (!\is_array($sections) && $sections instanceof Section) {
            $sections = [$sections];
        }

        $this->view->assign('sections', $sections);
        $this->view->assign('rootSectionGroups', $sectionGroupRegistry->getRootSectionGroups());
    }
}
