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
 * @var $this Mage_Core_Model_Resource_Setup
 */

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('things/thing'))
    ->addColumn(
        'entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Thing ID'
    )
    ->addColumn(
        'name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Name'
    )
    ->addColumn(
        'description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Short description'
    )
    ->addColumn(
        'text', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Text'
    )
    ->addColumn(
        'start_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'Start date'
    )
    ->addColumn(
        'end_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'End date'
    )
    ->addColumn(
        'created_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'Created date'
    )
    ->addColumn(
        'is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 1, array(), 'Is active'
    )
    ->addColumn(
        'file', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Linked file name'
    )
    ->addColumn(
        'image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Linked image name'
    )
    ->setComment('Things entities');
$installer->getConnection()->createTable($table);
$installer->endSetup();
