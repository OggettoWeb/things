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
 * Thing category model test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Model_Category extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Test provides save and load options
     * 
     * @return void
     * @loadFixture
     */
    public function testProvidesSaveAndLoadOptions()
    {
        $category = Mage::getModel('things/category')->load(1);
        $this->assertEquals('foo', $category->getName());
        $category->setName('bar')->save();

        $category = Mage::getModel('things/category')->load(1);
        $this->assertEquals('bar', $category->getName());
    }

    /**
     * Test providers data collection retreiving option 
     * 
     * @return void
     * @loadFixture
     */
    public function testProvidesDataCollectionRetreivingOption()
    {
        $category = Mage::getModel('things/category')->getCollection();
        $this->assertEquals(array('foo', 'bar'), $category->getColumnValues('name'));
    }

    /**
     * Test provides remove option 
     * 
     * @return void
     * @loadFixture
     */
    public function testProvidesRemoveOption()
    {
        Mage::getModel('things/category')->load(1)->delete();
        $category = Mage::getModel('things/category')->load(1);
        $this->assertNull($category->getId());
    }

    /**
     * Test saves empty parent as null 
     * 
     * @return void
     */
    public function testSavesEmptyParentAsNull()
    {
        $category = Mage::getModel('things/category')->setParentId(0)->save();
        $this->assertNull($category->getParentId());
    }

    /**
     * Test saves path using parent path 
     * 
     * @return void
     */
    public function testSavesPathUsingParentPath()
    {
        $parent = Mage::getModel('things/category')->setName('foo')->save();
        $child = Mage::getModel('things/category')->setParentId($parent->getId())->save();

        $parent = Mage::getModel('things/category')->load($parent->getId());
        $child = Mage::getModel('things/category')->load($child->getId());
        $this->assertEquals($parent->getId(), $parent->getPath());
        $this->assertEquals("{$parent->getId()}/{$child->getId()}", $child->getPath());
    }

    /**
     * Test filters things by category in resource 
     * 
     * @return void
     */
    public function testFiltersThingsByCategoryInResource()
    {
        $category = 42;
        $things = Mage::getResourceModel('things/thing_collection');
        $resource = $this->getResourceModelMock('things/category', array('filterThingsByCategory'));
        $resource->expects($this->once())->method('filterThingsByCategory')
            ->with($this->equalTo($things), $this->equalTo($category));
        $this->replaceByMock('resource_model', 'things/category', $resource);

        Mage::getModel('things/category')->filterThingsByCategory($things, $category);
    }
}
