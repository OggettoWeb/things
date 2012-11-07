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
 * Things list block test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Block_List extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Create list block  
     * 
     * @return Oggetto_Things_Block_List
     */
    protected function _list()
    {
        return Mage::app()->getLayout()->createBlock('things/list');
    }

    /**
     * Test retreives things collection 
     * 
     * @return void
     */
    public function testRetreivesThingsCollection()
    {
        $this->assertInstanceOf(
            'Oggetto_Things_Model_Resource_Thing_Collection',
            $this->_list()->getCollection()
        );
    }

    /**
     * Test uses only active things 
     * 
     * @return void
     */
    public function testUsesOnlyActiveThings()
    {
        $collection = $this->getResourceModelMock('things/thing_collection', array('addActiveFilter'));
        $collection->expects($this->once())->method('addActiveFilter')
            ->will($this->returnValue($collection));
        $this->replaceByMock('resource_model', 'things/thing_collection', $collection);

        $this->_list()->getCollection();
    }

    /**
     * Test provides sorting option
     * 
     * @return void
     */
    public function testProvidesSortingOption()
    {
        $collection = $this->getResourceModelMock('things/thing_collection', array('setOrder'));
        $collection->expects($this->once())->method('setOrder')
            ->with($this->equalTo('foo'), $this->equalTo('desc'))
            ->will($this->returnValue($collection));
        $this->replaceByMock('resource_model', 'things/thing_collection', $collection);

        $this->_list()
            ->setSort('foo')
            ->setSortDir('desc')
            ->getCollection();
    }

    /**
     * Test provides dates filtering option 
     *
     * @param string $field Date field
     * @param string $from  From date
     * @param string $to    To Date
     * 
     * @return void
     * @dataProvider dataProvider
     * @loadExpectation
     */
    public function testProvidesDatesFilteringOption($field, $from, $to)
    {
        $collection = $this->getResourceModelMock('things/thing_collection', array('addDateFilter'));
        $collection->expects($this->once())->method('addDateFilter')
            ->with($this->equalTo($field), $this->equalTo($from), $this->equalTo($to))
            ->will($this->returnValue($collection));
        $this->replaceByMock('resource_model', 'things/thing_collection', $collection);

        $this->_list()
            ->setData("{$field}_from", $from)
            ->setData("{$field}_to", $to)
            ->getCollection();
    }

    /**
     * Test providers current date filtering option 
     * 
     * @param mixed $field Date field
     * @return void
     * @dataProvider dataProvider
     */
    public function testProvidesCurrentDateFilteringOption($field)
    {
        $now = new Zend_Date('2011-01-01', Varien_Date::DATE_INTERNAL_FORMAT);
        $locale = $this->getModelMock('core/locale', array('storeDate'));
        $locale->expects($this->any())->method('storeDate')->will($this->returnValue($now));
        $this->replaceByMock('singleton', 'core/locale', $locale);

        $collection = $this->getResourceModelMock('things/thing_collection', array('addDateFilter'));
        $collection->expects($this->once())->method('addDateFilter')
            ->with($this->equalTo($field), $this->equalTo('2011-01-01'), $this->equalTo('2011-01-01'))
            ->will($this->returnValue($collection));
        $this->replaceByMock('resource_model', 'things/thing_collection', $collection);

        $this->_list()
            ->setData("{$field}_from", 'now')
            ->setData("{$field}_to", 'now')
            ->getCollection();
    }

    /**
     * Test providers limiting option 
     * 
     * @return void
     */
    public function testProvidersLimitingOption()
    {
        $collection = $this->getResourceModelMock('things/thing_collection', array('setPageSize'));
        $collection->expects($this->once())->method('setPageSize')
            ->with($this->equalTo(42))
            ->will($this->returnValue($collection));
        $this->replaceByMock('resource_model', 'things/thing_collection', $collection);

        $this->_list()
            ->setLimit(42)
            ->getCollection();
    }

    /**
     * Test provides category filtering option 
     * 
     * @return void
     */
    public function testProvidesCategoryFilteringOption()
    {
        $category = $this->getModelMock('things/category', array('filterThingsByCategory'));
        $category->expects($this->once())->method('filterThingsByCategory')
            ->with($this->isInstanceOf('Oggetto_Things_Model_Resource_Thing_Collection'), $this->equalTo(42));
        $this->replaceByMock('model', 'things/category', $category);

        $this->_list()
            ->setCategory(42)
            ->getCollection();
    }

    /**
     * Test renders pager html configured by block params
     * 
     * @return void
     */
    public function testRendersPagerHtmlConfiguredByBlockParams()
    {
        $pager = $this->getBlockMock('page/html_pager', array('setCollection', '_toHtml'));
        $pager->expects($this->once())->method('setCollection')
            ->with($this->isInstanceOf('Oggetto_Things_Model_Resource_Thing_Collection'))
            ->will($this->returnValue($pager));
        $pager->expects($this->any())->method('_toHtml')->will($this->returnValue('<html>'));
        $this->replaceByMock('block', 'page/html_pager', $pager);

        $blockParams = array(
            'pager_config[template]'       => 'foo.phtml',
            'pager_config[page_var_name]'  => 'page',
            'pager_config[limit_var_name]' => 'limit'
        );
        $list = $this->_list()->setBlockParams($blockParams);
        $this->_assertPagerHasParams(array(
            'template'       => 'foo.phtml',
            'page_var_name'  => 'page',
            'limit_var_name' => 'limit'
        ), $list->getPager());
        $this->assertEquals('<html>', $list->getPagerHtml());
    }

    /**
     * Test renders pager html configured by direct parametrizing 
     * 
     * @return void
     */
    public function testRendersPagerHtmlConfiguredByDirectParametrizing()
    {
        $pager = $this->getBlockMock('page/html_pager', array('_toHtml'));
        $pager->expects($this->any())->method('_toHtml')->will($this->returnValue('<html>'));
        $this->replaceByMock('block', 'page/html_pager', $pager);

        $pagerParams = array(
            'template'       => 'foo.phtml',
            'page_var_name'  => 'page',
            'limit_var_name' => 'limit'
        );
        $list = $this->_list()->setPagerConfig($pagerParams);
        $this->_assertPagerHasParams($pagerParams, $list->getPager());
        $this->assertEquals('<html>', $list->getPagerHtml());
    }

    /**
     * Assert pager has params 
     * 
     * @param array                      $params Expected params
     * @param Mage_Page_Block_Html_Pager $pager  Pager
     * @return void
     */
    protected function _assertPagerHasParams($params, $pager)
    {
        foreach ($params as $_key => $_value) {
            $this->assertEquals($_value, $pager->getDataUsingMethod($_key));
        }
    }
}
