<?php 
/**
 * Oggetto Web things extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto Address module to newer versions in the future.
 * If you wish to customize the Oggetto Things module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Things category edit form block test case
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2011 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Oggetto_Things_Test_Block_Adminhtml_Category_Edit_Form extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Test renders category fields 
     * 
     * @return void
     * @loadExpectation
     */
    public function testRendersCategoryFields()
    {
        $this->replaceRegistry('current_category', Mage::getModel('things/category'));
        $form = Mage::app()->getLayout()->createBlock('things/adminhtml_category_edit_form');
        $form->toHtml();

        $fieldset = $form->getForm()->getElement('base_fieldset');
        $elements = array_map(function($element) {
            return $element->getId();
        }, $fieldset->getSortedElements());

        $this->assertEquals($this->expected()->getElements(), $elements);
    }
}
