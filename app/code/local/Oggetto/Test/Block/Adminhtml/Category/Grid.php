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
 * Things categories grid block test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Block_Adminhtml_Category_Grid extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Grid instance 
     * 
     * @var Oggetto_Things_Block_Adminhtml_Category_Grid
     */
    protected $_grid;

    /**
     * Set up 
     * 
     * @return void
     */
    public function setUp()
    {
        $this->_grid = Mage::app()->getLayout()->createBlock('things/adminhtml_category_grid');
    }

    /**
     * Test uses categories collection 
     * 
     * @return void
     */
    public function testUsesCategoriesCollection()
    {
        $this->_grid->toHtml();
        $this->assertInstanceOf('Oggetto_Things_Model_Resource_Category_Collection', $this->_grid->getCollection());
    }

    /**
     * Test displays categories columns 
     * 
     * @return void
     * @loadExpectation
     */
    public function testDisplaysCategoriesColumns()
    {
        $this->_grid->toHtml();
        $this->assertEquals($this->expected()->getColumns(), array_keys($this->_grid->getColumns()));
    }
}
