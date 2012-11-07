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
 * Edit thing entity form block
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Block
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Block_Adminhtml_Thing_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init the form 
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('thing_form');
    }

    /**
     * Load Wysiwyg on demand and Prepare layout
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->setCanLoadTinyMce(true);
        }
    }

    /**
     * Prepare form 
     * 
     * @return Oggetto_Things_Block_Adminhtml_Thing_Edit_Form
     */
    protected function _prepareForm()
    {
        $thing = Mage::registry('current_thing');
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
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

        $fieldset->addField('is_active', 'select',
            array(
                'name'  => 'is_active',
                'label' => $helper->__('Is Active'),
                'title' => $helper->__('Is Active'),
                'options' => array(
                    $helper->__('No'),
                    $helper->__('Yes')
                )
            )
        );
        
        $fieldset->addField('url_key', 'text',
            array(
                'name'     => 'url_key',
                'label'    => $helper->__('URL key'),
                'title'    => $helper->__('URL key'),
            )
        );

        $fieldset->addField('text', 'editor',
            array(
                'name'   => 'text',
                'label'  => $helper->__('Text'),
                'title'  => $helper->__('Text'),
                'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                'style'  => 'width: 530px;height:300px;'
            )
        );

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('created_date', 'date',
            array(
                'name'  => 'created_date',
                'label' => $helper->__('Created Date'),
                'title' => $helper->__('Created Date'),
                'image'    => $this->getSkinUrl('images/grid-cal.gif'),
                'format'   => $dateFormatIso,
            )
        );

        $fieldset->addField('start_date', 'date',
            array(
                'name'  => 'start_date',
                'label' => $helper->__('Start Date'),
                'title' => $helper->__('Start Date'),
                'image'    => $this->getSkinUrl('images/grid-cal.gif'),
                'format'   => $dateFormatIso,
            )
        );

        $fieldset->addField('end_date', 'date',
            array(
                'name'  => 'end_date',
                'label' => $helper->__('End Date'),
                'title' => $helper->__('End Date'),
                'image'    => $this->getSkinUrl('images/grid-cal.gif'),
                'format'   => $dateFormatIso,
            )
        );

        $categories =
            array($helper->__('None')) +
            Mage::getModel('things/category')->getCollection()->toOptionHash();
        $fieldset->addField('category_id', 'select',
            array(
                'name'    => 'category_id',
                'label'   => $helper->__('Category'),
                'title'   => $helper->__('Category'),
                'options' => $categories
            )
        );

        $media = Mage::helper('things/media');
        $fieldset->addField('file', 'file',
            array(
                'name'  => 'file',
                'label' => $helper->__('File'),
                'title' => $helper->__('File'),
                'note'  => $thing->getFile() ? 
                    $helper->__('Current file: %s', $media->getFileUrl($thing->getFile())) : null
            )
        );

        $fieldset->addField('image', 'image',
            array(
                'name'  => 'image',
                'label' => $helper->__('Image'),
                'title' => $helper->__('Image'),
                'value' => $thing->getImage() ? $media->getImageMediaPath($thing->getImage()) : null
            )
        );

        if ($thing->getId()) {
            $fieldset->addField('thing_id', 'hidden',
                array(
                    'name'    => 'thing_id',
                    'value'   => $thing->getId(),
                    'no_span' => true
                )
            );
        }

        $form->setAction($this->getUrl('*/*/save'));
        $formData = $thing->getData();
        unset($formData['image']);
        $form->addValues($formData);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
