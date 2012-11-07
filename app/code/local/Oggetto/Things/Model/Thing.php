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
 * Thing entity
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Model
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Model_Thing extends Mage_Core_Model_Abstract
{
    /**
     * Thing entity code  
     */
    const ENTITY = 'thing';

    /**
     * Init the resource
     * 
     * @return void 
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('things/thing');
    }

    /**
     * Process data before save
     * 
     * @return Oggetto_Things_Model_Thing
     */
    protected function _beforeSave()
    {
        if (!$this->getCategoryId()) {
            $this->setCategoryId(null);
        }
        return parent::_beforeSave();
    }

    /**
     * Process data after save
     *
     * @return Oggetto_Things_Model_Thing
     */
    public function afterCommitCallback()
    {
        Mage::getSingleton('index/indexer')->processEntityAction(
            $this, self::ENTITY, Mage_Index_Model_Event::TYPE_SAVE
        );
        return $this;
    }

    /**
     * Get parsed thing text 
     * 
     * @return string
     */
    public function getParsedText()
    {
        return Mage::helper('cms/data')->getBlockTemplateProcessor()->filter($this->getText());
    }
}
