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
 * Edit thing category entity form block
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Block
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Block_Adminhtml_Category_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init the form 
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('category_form');
    }

    /**
     * Prepare form 
     * 
     * @return Oggetto_Things_Block_Adminhtml_Category_Edit_Form
     */
    protected function _prepareForm()
    {
        $category = Mage::registry('current_category');
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'method'    => 'post',
        ));

        $helper = $this->helper('things');
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $helper->__('Info'),
            'title' => $helper->__('Info')
        ));

        $fieldset->addField('name', 'text',
            array(
                'name'     => 'name',
                'label'    => $helper->__('Name'),
                'title'    => $helper->__('Name'),
                'class'    => 'required-entry',
                'required' => true,
            )
        );

        $fieldset->addField('description', 'text',
            array(
                'name'  => 'description',
                'label' => $helper->__('Short Description'),
                'title' => $helper->__('Short Description'),
            )
        );

        $categories = array($helper->__('None')) +
            Mage::getModel('things/category')->getCollection()->toOptionHash();
        $fieldset->addField('parent_id', 'select',
            array(
                'name'    => 'parent_id',
                'label'   => $helper->__('Parent Category'),
                'title'   => $helper->__('Parent Category'),
                'options' => $categories
            )
        );

        if ($category->getId()) {
            $fieldset->addField('category_id', 'hidden',
                array(
                    'name'    => 'category_id',
                    'value'   => $category->getId(),
                    'no_span' => true
                )
            );
        }

        $form->setAction($this->getUrl('*/*/save'));
        $form->addValues($category->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
