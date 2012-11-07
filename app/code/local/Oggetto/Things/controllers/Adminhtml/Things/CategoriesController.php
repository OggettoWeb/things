<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @copyright  Copyright (C) 2012 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Things categories controller
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage controller
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Adminhtml_Things_CategoriesController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init action
     * 
     * @return void
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/things');
        $this->_title($this->__('CMS'))
             ->_title($this->__('Dynamic Content'))
             ->_title($this->__('Categories'));
    }

    /**
     * Things categories grid page
     * 
     * @return void
     */
    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Create new category action
     *
     * @return void
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit action
     *
     * @return void
     */
    public function editAction()
    {
        $category = Mage::getModel('things/category');
        $categoryId = $this->getRequest()->getParam('id', null);

        if ($categoryId) {
            $category->load($categoryId);
            if (!$category->getId()) {
                $this->_getSession()->addError($this->__('This item no longer exists'));
                $this->_redirect('*/*');
                return;
            }
        }

        $this->_initAction();
        $this->_title($category->getId() ? $category->getName() : $this->__('New Category'));
        Mage::register('current_category', $category);
        $this->renderLayout();
    }

    /**
     * Save category 
     * 
     * @return void
     */
    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $category = Mage::getModel('things/category');
                if ($id = $this->getRequest()->getParam('category_id')) {
                    $category->load($id);
                }

                $category->addData($this->getRequest()->getPost());
                $category->save();

                $this->_getSession()->addSuccess($this->__('Category was successfully saved.'));
                $this->_redirect('*/*/index');
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirectReferer();
            }
        } else {
            $this->_redirectReferer();
        }
    }

    /**
     * Delete category 
     * 
     * @return void
     */
    public function deleteAction()
    {
        try {
            $category = Mage::getModel('things/category')->load($this->getRequest()->getParam('id', null));
            if (!$category->getId()) {
                Mage::throwException($this->__('Item no longer exists.'));
            }
            $category->delete();
            $this->_getSession()->addSuccess($this->__('Category was successfully deleted.'));
            $this->_redirect('*/*/index');
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer();
        }
    }
}
