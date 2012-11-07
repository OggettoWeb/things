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
 * Things collection model test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Model_Resource_Thing_Collection extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Test filters things by active 
     * 
     * @return void
     * @loadFixture
     */
    public function testFiltersThingsByActive()
    {
        $collection = Mage::getResourceModel('things/thing_collection')
            ->addActiveFilter();
        $this->assertEquals(array(1, 2, 3), $collection->getAllIds());
    }

    /**
     * Test filters things by dates 
     *
     * @param string $dateField Date field
     * @param string $from      From-date filter value
     * @param string $to        To-date filter value
     * 
     * @return void
     * @loadFixture
     * @dataProvider dataProvider
     * @loadExpectation
     */
    public function testFiltersThingsByDates($dateField, $from, $to)
    {
        $collection = Mage::getResourceModel('things/thing_collection')
            ->addDateFilter($dateField, $from, $to);
        $expected = $this->expected('%s_from_%s_to_%s', $dateField, $from, $to)->getIds();
        $this->assertEquals($expected, $collection->getAllIds());
    }
}
