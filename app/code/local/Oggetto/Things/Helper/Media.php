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
 * Things media helper
 *
 * @category   Oggetto
 * @package    Oggetto_Things
 * @subpackage Helper
 * @author     Dan Kocherga <dan@oggettoweb.com>
 */
class Oggetto_Things_Helper_Media extends Mage_Core_Helper_Data
{
    /**
     * Get things image url path relative to
     * media folder
     * 
     * @param string $filename Filename
     * @return string
     */
    public function getImageMediaPath($filename)
    {
        return "things/images/{$filename}";
    }

    /**
     * Get url to things media file
     * 
     * @param string $filename Filename
     * @return string
     */
    public function getFileUrl($filename)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "things/files/{$filename}";
    }

    /**
     * Get url to things meida image 
     * 
     * @param string $filename Filename
     * @return string
     */
    public function getImageUrl($filename)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $this->getImageMediaPath($filename);
    }

    /**
     * Get things media dir  
     * 
     * @param string $folder Folder name
     * @return string
     */
    public function getMediaDir($folder)
    {
        return Mage::getBaseDir('media') . DS . 'things' . DS . $folder;
    }
}
