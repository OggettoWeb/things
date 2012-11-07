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
 * Things category resource
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Model
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Model_Resource_Category
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Init the table and primary key
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('things/category', 'entity_id');
    }

    /**
     * Filter things by category 
     * 
     * @param Oggetto_Things_Model_Resource_Thing_Collection $things     Things
     * @param integer                                        $categoryId Category ID
     * @return void
     */
    public function filterThingsByCategory($things, $categoryId)
    {
        $conditions = array(
            'cat_idx.thing_id = main_table.entity_id',
            $this->getReadConnection()->quoteInto('cat_idx.category_id = ?', $categoryId)
        );
        $things->join(array('cat_idx' => 'things/category_thing_index'), implode(' AND ', $conditions), array());
    }

    /**
     * Update category path
     *
     * @param Oggetto_Things_Model_Category $object Category
     * @return Oggetto_Things_Model_Resource_Category
     */
    public function updatePath(Oggetto_Things_Model_Category $object)
    {
        if ($object->getParentId()) {
            $select = $this->_getReadAdapter()
                ->select()->from($this->getMainTable(), array('path'))
                ->where('entity_id = ?', $object->getParentId());
            $parentPath = $this->_getReadAdapter()->fetchOne($select);
            $path = $parentPath . '/' . $object->getId();
        } else {
            $path = $object->getId();
        }

        $this->_getWriteAdapter()->update(
            $this->getMainTable(),
            array('path' => $path),
            array('entity_id = ?' => $object->getId())
        );
        return $path;
    }
}
