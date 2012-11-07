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
 * Category things index resource
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Model
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Model_Resource_Index_Category_Thing 
    extends Mage_Index_Model_Resource_Abstract
{
    /**
     * Init connection prefix 
     * 
     * @return void
     */
    protected function _construct()
    {
        $this->_init('things/category_thing_index', 'category_id');
    }

    /**
     * Process thing save reindex 
     * 
     * @param Mage_Index_Model_Event $event Event
     * @return void
     */
    public function thingSave($event)
    {
        $thingId = $event->getThingId();
        $newCategoryId = $event->getCategoryId();
        if (!$thingId) {
            return;
        }

        // Delete old relations
        $this->_getWriteAdapter()->delete($this->getMainTable(), array('thing_id = ?' => $thingId));

        // Index new relations
        $this->_assignThingsToCategory(array($thingId), $newCategoryId);
    }

    /**
     * Process thing category save reindex 
     * 
     * @param Mage_Index_Model_Event $event Event
     * @return void
     */
    public function thingCategorySave($event)
    {
        $categoryId = $event->getCategoryId();
        $newParentId = $event->getParentId();
        if (!$categoryId) {
            return;
        }

        // Find assigned things
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getTable('things/thing'), array('entity_id'))
            ->where('category_id=?', $categoryId);
        $assignedThings = $this->_getReadAdapter()->fetchCol($select);

        // Delete old relations of assigned things
        $conditions = array(
            $this->_getReadAdapter()->quoteInto('thing_id IN(?)', $assignedThings),
            $this->_getReadAdapter()->quoteInto('category_id != ?', $categoryId)
        );
        $this->_getWriteAdapter()->delete($this->getMainTable(), implode(' AND ', $conditions));

        // Assign things to new parent
        $this->_assignThingsToCategory($assignedThings, $newParentId);
    }

    /**
     * Reindex all relations
     * 
     * @return void
     */
    public function reindexAll()
    {
        $this->_getWriteAdapter()->truncate($this->getMainTable());
        $categories = Mage::getResourceModel('things/category_collection');
        foreach ($categories as $_category) {
            $things = Mage::getResourceModel('things/thing_collection')
                ->addFieldToFilter('category_id', $_category->getId());
            $this->_assignThingsToCategory($things->getAllIds(), $_category->getId());
        }
    }

    /**
     * Assign things to category
     * 
     * @param integer $thingsIds  Things IDs
     * @param array   $categoryId Category ID
     * @return void
     */
    protected function _assignThingsToCategory($thingsIds, $categoryId)
    {
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getTable('things/category'), array('path'))
            ->where('entity_id = ?', $categoryId);
        $path = $this->_getReadAdapter()->fetchOne($select);
        if (!$path) {
            return;
        }
        $categories = explode('/', $path);
        $index = array();
        foreach ($categories as $_categoryId) {
            foreach ($thingsIds as $_thingId) {
                $index[] = array(
                    'category_id' => $_categoryId,
                    'thing_id'    => $_thingId
                );
            }
        }
        if ($index) {
            $this->_getWriteAdapter()->insertMultiple($this->getMainTable(), $index);
        }
    }
}
