<?php 
/**
 * Oggetto Web things extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto Address module to newer versions in the future.
 * If you wish to customize the Oggetto Things module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Thing model test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Model_Thing extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Test provides save and load options
     * 
     * @return void
     * @loadFixture
     */
    public function testProvidesSaveAndLoadOptions()
    {
        $thing = Mage::getModel('things/thing')->load(1);
        $this->assertEquals('foo', $thing->getName());
        $thing->setName('bar')->save();

        $thing = Mage::getModel('things/thing')->load(1);
        $this->assertEquals('bar', $thing->getName());
    }

    /**
     * Test providers data collection retreiving option 
     * 
     * @return void
     * @loadFixture
     */
    public function testProvidesDataCollectionRetreivingOption()
    {
        $things = Mage::getModel('things/thing')->getCollection();
        $this->assertEquals(array('foo', 'bar'), $things->getColumnValues('name'));
    }

    /**
     * Test provides remove option 
     * 
     * @return void
     * @loadFixture
     */
    public function testProvidesRemoveOption()
    {
        Mage::getModel('things/thing')->load(1)->delete();
        $thing = Mage::getModel('things/thing')->load(1);
        $this->assertNull($thing->getId());
    }

    /**
     * Test saves empty category as null 
     * 
     * @return void
     */
    public function testSavesEmptyCategoryAsNull()
    {
        $thing = Mage::getModel('things/thing')->setCategoryId(0)->save();
        $this->assertNull($thing->getCategoryId());
    }

    /**
     * Test passes main text through parser 
     * 
     * @return void
     */
    public function testPassesMainTextThroughParser()
    {
        $text = 'foo';
        $parsed = 'bar';
        $processor = $this->getMock('Varien_Filter_Template', array('filter'));
        $processor->expects($this->any())->method('filter')
            ->with($this->equalTo($text))
            ->will($this->returnValue($parsed));
        $cmsHelper = $this->getHelperMock('cms/data', array('getBlockTemplateProcessor'));
        $cmsHelper->expects($this->any())->method('getBlockTemplateProcessor')
            ->will($this->returnValue($processor));
        $this->replaceByMock('helper', 'cms/data', $cmsHelper);

        $thing = Mage::getModel('things/thing')->setText($text);
        $this->assertEquals($parsed, $thing->getParsedText());
    }
}
