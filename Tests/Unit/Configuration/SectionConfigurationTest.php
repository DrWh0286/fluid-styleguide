<?php
/***********************************************************************
 *
 * (c) 2018 Sebastian Hofer, Pluswerk AG (sebastian.hofer@pluswerk.ag)
 * All rights reserved
 *
 * You may not remove or change the name of the author above. See:
 * http://www.gnu.org/licenses/gpl-faq.html#IWantCredit
 *
 * This script is part of the Typo3 project. The Typo3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 *******************************************************************/

namespace Pluswerk\FluidStyleguide\Test\Unit\Configuration;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Pluswerk\FluidStyleguide\Configuration\SectionConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class SectionConfigurationTest extends UnitTestCase
{
    /**
     * @var SectionConfiguration
     */
    protected $sectionConfiguration;

    /**
     * @var vfsStreamDirectory
     */
    protected $fileSystem;

    public function setUp()
    {
        $this->fileSystem = vfsStream::setup('root', 444, $this->getFileSystem());
        $path = $this->fileSystem->url() . '/TestConfiguration.json';
        $this->sectionConfiguration = new SectionConfiguration($path);
    }

    /**
     * @test
     * @dataProvider getSectionDummyDataReturnsArrayOfDummyDataProvider
     */
    public function getSectionDummyDataReturnsArrayOfDummyData($expected, $fileName)
    {
        $path = $this->fileSystem->url() . '/' . $fileName;
        $sectionConfiguration = new SectionConfiguration($path);
        $this->assertEquals($expected, $sectionConfiguration->getSectionDummyData());
    }

    public function getSectionDummyDataReturnsArrayOfDummyDataProvider()
    {
        return [
            [
                'expected' => [
                    [
                        'testField' => 'This is a test dummy data'
                    ]
                ],
                'fileName' => 'TestConfiguration.json'
            ],
            [
                'expected' => [],
                'fileName' => 'NoData.json'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getRenderCodeBlockReturnsBooleanFromConfigFileOrDefaultProvider
     */
    public function getRenderCodeBlockReturnsBooleanFromConfigFileOrDefault($expected, $fileName)
    {
        $path = $this->fileSystem->url() . '/' . $fileName;
        $sectionConfiguration = new SectionConfiguration($path);

        if ($expected) {
            $this->assertTrue($sectionConfiguration->getRenderCodeBlock());
        } else {
            $this->assertFalse($sectionConfiguration->getRenderCodeBlock());
        }
    }

    public function getRenderCodeBlockReturnsBooleanFromConfigFileOrDefaultProvider()
    {
        return [
            [
                'expected' => true,
                'fileName' => 'TestConfiguration.json'
            ],
            [
                'expected' => false,
                'fileName' => 'CodeBlockFalse.json'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getDevelopmentStateReturnsPredefinedStatesOrUndefinedProvider
     */
    public function getDevelopmentStateReturnsPredefinedStatesOrUndefined($expected, $fileName)
    {
        $path = $this->fileSystem->url() . '/' . $fileName;
        $sectionConfiguration = new SectionConfiguration($path);
        $this->assertEquals($expected, $sectionConfiguration->getDevelopmentState());
    }

    public function getDevelopmentStateReturnsPredefinedStatesOrUndefinedProvider()
    {
        return [
            [
                'expected' => SectionConfiguration::DEVELOPMENT_STATE_UNDEFINED,
                'fileName' => 'TestConfiguration.json'
            ],
            [
                'expected' => SectionConfiguration::DEVELOPMENT_STATE_READY_TO_USE,
                'fileName' => 'ReadyToUse.json'
            ],
            [
                'expected' => SectionConfiguration::DEVELOPMENT_STATE_UNDER_REVIEW,
                'fileName' => 'UnderReview.json'
            ],
            [
                'expected' => SectionConfiguration::DEVELOPMENT_STATE_IN_PROGRESS,
                'fileName' => 'InProgress.json'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getComponentDocReturnsGivenDocumentationTextProvider
     */
    public function getComponentDocReturnsGivenDocumentationText($expected, $fileName)
    {
        $path = $this->fileSystem->url() . '/' . $fileName;
        $sectionConfiguration = new SectionConfiguration($path);
        $this->assertEquals($expected, $sectionConfiguration->getComponentDoc());
    }

    public function getComponentDocReturnsGivenDocumentationTextProvider()
    {
        return [
            [
                'expected' => 'This is the test component documentation',
                'fileName' => 'TestConfiguration.json'
            ],
            [
                'expected' => '',
                'fileName' => 'NoData.json'
            ]
        ];
    }

    /**
     * @return array
     */
    private function getFileSystem(): array
    {
        return [
            'TestConfiguration.json' => '
            {
                "renderCodeBlock": true,
                "componentDoc": "This is the test component documentation",
                "data": [
                    {
                        "testField": "This is a test dummy data"
                    }
                ]
            }
            ',
            'NoData.json' => '
            {
            }
            ',
            'CodeBlockFalse.json' => '
            {
                "renderCodeBlock": false,
                "componentDoc": "This is the test component documentation"
            }',
            'ReadyToUse.json' => '
            {
                "developmentState": "' . SectionConfiguration::DEVELOPMENT_STATE_READY_TO_USE . '"
            }
            ',
            'UnderReview.json' => '
            {
                "developmentState": "' . SectionConfiguration::DEVELOPMENT_STATE_UNDER_REVIEW . '"
            }
            ',
            'InProgress.json' => '
            {
                "developmentState": "' . SectionConfiguration::DEVELOPMENT_STATE_IN_PROGRESS . '"
            }
            '
        ];
    }
}
