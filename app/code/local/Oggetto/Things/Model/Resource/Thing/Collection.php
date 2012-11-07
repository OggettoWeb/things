<?php
/**
 * Oggetto Web things extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto Things module to newer versions in the future.
 * If you wish to customize the Oggetto Things module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2012 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Things collection class
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Model
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Model_Resource_Thing_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Init the resource 
     * 
     * @return void
     */
    protected function _construct() 
    {
        $this->_init('things/thing');
    }

    /**
     * Filter things by active only 
     * 
     * @return Oggetto_Things_Model_Resource_Thing_Collection
     */
    public function addActiveFilter()
    {
        return $this->addFieldToFilter('is_active', 1);
    }

    /**
     * Add date filter 
     * 
     * @param string $date Date field
     * @param string $from From filter value (in internal DB format)
     * @param string $to   To   filter value (in internal DB format)
     *
     * @return Oggetto_Things_Model_Resource_Thing_Collection
     */
    public function addDateFilter($date, $from, $to)
    {
        if ($from) {
            $this->addFieldToFilter($date, array('from' => $from, 'date' => true));
        }
        if ($to) {
            $this->addFieldToFilter($date, array('to' => $to, 'date' => true));
        }
        return $this;
    }
}
