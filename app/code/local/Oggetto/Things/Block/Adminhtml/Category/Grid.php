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
 * Things categories grid
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Block
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Block_Adminhtml_Category_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Init the grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('things_categories_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * Prepare grid collection
     *
     * @return Oggetto_Things_Block_Adminhtml_Category_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('things/category')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Retrieve URL for Row click
     *
     * @param Varien_Object $row Row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'id'    => $row->getId()
        ));
    }

    /**
     * Define grid columns
     *
     * @return Oggetto_Things_Block_Adminhtml_Thing_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => $this->helper('things')->__('ID'),
            'index'  => 'entity_id',
            'type'   => 'text',
            'width'  => 20,
        ));

        $this->addColumn('name', array(
            'header' => $this->helper('things')->__('Name'),
            'index'  => 'name',
            'type'   => 'text',
        ));

        $this->addColumn('action',
            array(
                'header'  => $this->helper('things')->__('Action'),
                'width'   => '100px',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->helper('things')->__('Edit'),
                        'url'     => array('base' => '*/*/edit'),
                        'field'   => 'id',
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
        ));
    }
}
