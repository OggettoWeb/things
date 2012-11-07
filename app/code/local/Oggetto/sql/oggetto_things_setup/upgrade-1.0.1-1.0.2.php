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
    ->newTable($installer->getTable('things/category'))
    ->addColumn(
        'entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Category ID'
    )
    ->addColumn(
        'name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Name'
    )
    ->addColumn(
        'description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Short description'
    )
    ->addColumn(
        'parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => true,
            'default'  => null
        ), 'Short description'
    )
    ->addForeignKey(
        $installer->getFkName('things/category', 'parent_id', 'things/category', 'entity_id'),
        'parent_id',
        $installer->getTable('things/category'),
        'entity_id', Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Things categories entities');
$installer->getConnection()->createTable($table);

$installer->getConnection()->addColumn(
    $installer->getTable('things/thing'),
    'category_id', array(
        'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'length'   => 11,
        'unsigned' => true,
        'comment'  => 'Category ID',
        'nullable' => true,
        'default'  => null
    )
);
$installer->getConnection()->addForeignKey(
    $installer->getFkName(
        'things/thing', 'category_id', 'things/category', 'entity_id'),
    $installer->getTable('things/thing'),
    'category_id',
    $installer->getTable('things/category'),
    'entity_id',
    Varien_Db_Adapter_Interface::FK_ACTION_SET_NULL
);
$installer->endSetup();
