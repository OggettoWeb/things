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
 * Edit thing entity block
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Block
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Block_Adminhtml_Thing_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Init the form container 
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_mode       = 'edit';
        $this->_controller = 'adminhtml_thing';
        $this->_blockGroup = 'things';

        parent::__construct();
        $this->_updateButton('save', 'label', $this->helper('things')->__('Save Post'));
        $this->_updateButton('delete', 'label', $this->helper('things')->__('Delete Post'));
        $this->_addButton('saveandcontinue', array(
            'label'     => $this->helper('things')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Get header text 
     * 
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_thing')->getId()) {
            return $this->helper('things')
                ->__("Edit Post '%s'", $this->htmlEscape(Mage::registry('current_thing')->getName()));
        } else {
            return $this->helper('things')->__('New Post');
        }
    }
}
