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
 * Things controller
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage controller
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Adminhtml_ThingsController extends Mage_Adminhtml_Controller_Action
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
             ->_title($this->__('Dynamic Content'));
    }

    /**
     * Things entities grid page 
     * 
     * @return void
     */
    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Create new entity action
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
        $thing = Mage::getModel('things/thing');
        $thingId = $this->getRequest()->getParam('id', null);

        if ($thingId) {
            $thing->load($thingId);
            if (!$thing->getId()) {
                $this->_getSession()->addError($this->__('This item no longer exists'));
                $this->_redirect('*/*');
                return;
            }
        }

        $this->_initAction();
        $this->_title($thing->getId() ? $thing->getName() : $this->__('New Post'));
        Mage::register('current_thing', $thing);
        $this->renderLayout();
    }

    /**
     * Save thing entity 
     * 
     * @return void
     */
    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $thing = Mage::getModel('things/thing');
                if ($id = $this->getRequest()->getParam('thing_id')) {
                    $thing->load($id);
                }

                $data = $this->getRequest()->getPost();
                $this->_uploadFile($thing, 'file', 'files');
                if (isset($data['image']['delete'])) {
                    $this->_deleteFile($thing, 'image', 'images');
                } else {
                    $this->_uploadFile($thing, 'image', 'images');
                }
                unset($data['image']);
                $data = $this->_filterDates($data, array('created_date', 'start_date', 'end_date'));
                $thing->addData($data);
                $thing->save();

                $this->_getSession()->addSuccess($this->__('Post was successfolly saved.'));
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $thing->getId()));
                } else {
                    $this->_redirect('*/*/index');
                }
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirectReferer();
            }
        } else {
            $this->_redirectReferer();
        }
    }
    
    /**
     * Upload file 
     * 
     * @param Oggetto_Things_Model_Thing $thing  Thing entity
     * @param string                     $field  File field
     * @param string                     $folder Folder name
     * @return void
     */
    protected function _uploadFile($thing, $field, $folder)
    {
        if (isset($_FILES[$field]) && $_FILES[$field]['tmp_name']) {
            $uploader = new Varien_File_Uploader($field);
            $uploader->setAllowRenameFiles(true);
            $uploader->save(Mage::helper('things/media')->getMediaDir($folder));
            $thing->setData($field, $uploader->getUploadedFileName());
        }
    }

    /**
     * Delete image file
     * 
     * @param Oggetto_Things_Model_Thing $thing  Thing entity
     * @param string                     $field  File field
     * @param string                     $folder Folder name
     * @return void
     */
    protected function _deleteFile($thing, $field, $folder)
    {
        $file = Mage::helper('things/media')->getMediaDir($folder) . DS . $thing->getData($field);
        if (file_exists($file)) {
            unlink($file);
        }
        $thing->setData($field, null);
    }

    /**
     * Delete thing 
     * 
     * @return void
     */
    public function deleteAction()
    {
        try {
            $thing = Mage::getModel('things/thing')->load($this->getRequest()->getParam('id', null));
            if (!$thing->getId()) {
                Mage::throwException($this->__('Item no longer exists.'));
            }
            $thing->delete();
            $this->_getSession()->addSuccess($this->__('Post was successfully deleted.'));
            $this->_redirect('*/*/index');
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer();
        }
    }
}
