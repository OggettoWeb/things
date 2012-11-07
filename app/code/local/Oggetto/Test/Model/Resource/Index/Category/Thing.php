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
 * Thing category index resource test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Model_Resource_Index_Category_Thing extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Test reindexes data for single thing 
     * 
     * @return void
     * @loadFixture
     * @loadExpectation
     */
    public function testReindexesDataForSingleThing()
    {
        $event = new Varien_Object;
        $event->setThingId(1)->setCategoryId(4);
        $resource = Mage::getResourceModel('things/index_category_thing');
        $resource->thingSave($event);
        $this->_assertIndexHasData($this->expected()->getData(), $resource);
    }

    /**
     * Test renidexes data for category 
     * 
     * @return void
     * @loadFixture
     * @loadExpectation
     */
    public function testRenidexesDataForCategory()
    {
        $event = new Varien_Object;
        $event->setCategoryId(3)->setParentId(4);
        $resource = Mage::getResourceModel('things/index_category_thing');
        $resource->thingCategorySave($event);
        $this->_assertIndexHasData($this->expected()->getData(), $resource);
    }

    /**
     * Test reindexes all data 
     * 
     * @return void
     * @loadExpectation
     * @loadFixture
     */
    public function testReindexesAllData()
    {
        $resource = Mage::getResourceModel('things/index_category_thing');
        $resource->reindexAll();
        $this->_assertIndexHasData($this->expected()->getData(), $resource);
    }

    /**
     * Assert that index has data 
     * 
     * @param array                                              $expected      Expected data
     * @param Oggetto_Things_Model_Resource_Index_Category_Thing $indexResource Resource
     * @return void
     */
    protected function _assertIndexHasData($expected, $indexResource)
    {
        $read = $indexResource->getReadConnection();
        $select = $read->select()->from($indexResource->getTable('things/category_thing_index'));
        $this->assertEquals($expected, $read->fetchAll($select));
    }
}
