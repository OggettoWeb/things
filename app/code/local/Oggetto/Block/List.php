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
 * Things list block
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Block
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Block_List extends Mage_Core_Block_Template
{
    /**
     * Get things collection 
     * 
     * @return Oggetto_Things_Model_Resource_Thing_Collection
     */
    public function getCollection()
    {
        return $this->_initCollection();
    }
    
    /**
     * Init configured things collection 
     * 
     * @return Oggetto_Things_Model_Resource_Thing_Collection
     */
    public function _initCollection()
    {
        if (!$this->hasData('collection')) {
            $collection = Mage::getModel('things/thing')
                ->getCollection()
                ->addActiveFilter();
            if ($this->getSort()) {
                $collection->setOrder($this->getSort(), $this->getSortDir() ?: 'asc');
            }
            if ($category = $this->getCategory()) {
                Mage::getModel('things/category')->filterThingsByCategory($collection, $category);
            }
            $this->_applyDateFilters($collection);
            $pager = $this->getPager();
            if ($this->getLimit()) {
                $pager->setLimit($this->getLimit());
            }
            $pager->setCollection($collection);
            $this->setData('collection', $collection);
        }
        return $this->getData('collection');
    }

    /**
     * Apply date filters 
     * 
     * @param Oggetto_Things_Model_Resource_Thing_Collection $collection Collection
     * @return void
     */
    protected function _applyDateFilters($collection)
    {
        $dates = array('created_date', 'start_date', 'end_date');
        foreach ($dates as $_date) {
            $from = $this->getData("{$_date}_from");
            $to   = $this->getData("{$_date}_to");
            if ($from || $to) {
                $collection->addDateFilter($_date, $this->_prepareDate($from), $this->_prepareDate($to));
            }
        }
    }

    /**
     * Prepare date filter value 
     * 
     * @param string $date Date string 
     * @return string
     */
    protected function _prepareDate($date)
    {
        $locale = Mage::getSingleton('core/locale');
        if ($date == 'now') {
            return $locale->storeDate()->get(Varien_Date::DATE_INTERNAL_FORMAT);
        }
        return $date;
    }

    /**
     * Get pager block instance 
     * 
     * @return Mage_Page_Block_Html_Pager
     */
    public function getPager()
    {
        if (!$this->hasData('pager')) {
            $pager = $this->getLayout()->createBlock('page/html_pager');
            foreach ($this->getPagerConfig() as $_field => $_value) {
                $pager->setDataUsingMethod($_field, $_value);
            }
            $this->setData('pager', $pager);
        }
        return $this->getData('pager');
    }

    /**
     * Get pager config 
     * 
     * @return array
     */
    public function getPagerConfig()
    {
        // Config may be specified directly as array
        if (!$this->hasData('pager_config')) {
            // Or as pseudo-array in block params (from admin cms static blocks)
            $config = array();
            if ($this->getBlockParams()) {
                foreach ($this->getBlockParams() as $_key => $_value) {
                    if (preg_match('/pager_config\[([a-z_]+)\]/', $_key, $mathces)) {
                        $config[$mathces[1]] = $_value;
                    }
                }
            }
            $this->setData('pager_config', $config);
        }
        return $this->getData('pager_config');
    }

    /**
     * Get pager html 
     * 
     * @return string
     */
    public function getPagerHtml()
    {
        $this->_initCollection();
        return $this->getPager()->toHtml();
    }
}
