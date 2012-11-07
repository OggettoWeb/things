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
 * Category-thing indexer model
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Model
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Model_Index_Category_Thing 
    extends Mage_Index_Model_Indexer_Abstract
{
    /**
     * Matched entities 
     * 
     * @var array
     */
    protected $_matchedEntities = array(
        Oggetto_Things_Model_Thing::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
        Oggetto_Things_Model_Category::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        )
    );

    /**
     * Init the resource 
     * 
     * @return void
     */
    protected function _construct()
    {
        $this->_init('things/index_category_thing');
    }

    /**
     * Get indexer name 
     * 
     * @return string
     */
    public function getName()
    {
        return Mage::helper('things')->__('Things Categories');
    }

    /**
     * Get indexer description 
     * 
     * @return string
     */
    public function getDescription()
    {
        return Mage::helper('things')->__('Things categories link index');
    }

    /**
     * Register indexer event 
     * 
     * @param Mage_Index_Model_Event $event Event
     * @return void
     */
    protected function _registerEvent(Mage_Index_Model_Event $event)
    {
        /* @var $entity Mage_Core_Model_Abstract */
        $entity = $event->getDataObject();
        switch ($event->getEntity()) {
            case Oggetto_Things_Model_Thing::ENTITY:
                if ($entity->dataHasChangedFor('category_id')) {
                    $event->setCategoryId($entity->getCategoryId())
                        ->setThingId($entity->getId());
                }
                break;
            case Oggetto_Things_Model_Category::ENTITY:
                if ($entity->dataHasChangedFor('parent_id')) {
                    $event->setParentId($entity->getParentId())
                        ->setCategoryId($entity->getId());
                }
                break;
        }
    }

    /**
     * Process event data and save to index
     *
     * @param Mage_Index_Model_Event $event Event
     * @return void
     */
    protected function _processEvent(Mage_Index_Model_Event $event)
    {
        $this->callEventHandler($event);
    }
}
