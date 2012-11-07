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
 * Thing category index test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Model_Index_Category_Thing extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Test reindexes thing data on save 
     * 
     * @return void
     * @loadFixture
     */
    public function testReindexesThingDataOnSave()
    {
        $indexer = $this->getResourceModelMock('things/index_category_thing', array('thingSave'));
        $indexer->expects($this->once())->method('thingSave');
        $this->replaceByMock('resource_singleton', 'things/index_category_thing', $indexer);

        Mage::getModel('things/thing')->setCategoryId(1)->save();
    }

    /**
     * Test reindexes category data on save 
     * 
     * @return void
     * @loadFixture
     */
    public function testReindexesCategoryDataOnSave()
    {
        $indexer = $this->getResourceModelMock('things/index_category_thing', array('thingCategorySave'));
        $indexer->expects($this->once())->method('thingCategorySave');
        $this->replaceByMock('resource_singleton', 'things/index_category_thing', $indexer);

        Mage::getModel('things/category')->setParentId(1)->save();
    }
}
