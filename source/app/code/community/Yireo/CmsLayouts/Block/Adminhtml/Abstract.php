<?php
/**
 * CmsLayouts extension for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_CmsLayouts_Block_Adminhtml_Abstract extends Mage_Adminhtml_Block_Widget_Container
{
    /*
     * Helper to return the header of this page
     */
    public function getHeader($title = null)
    {
        return 'CMS Layouts - '.$this->__($title);
    }

    /*
     * Helper to return the menu
     */
    public function getMenu()
    {
        return $this->getLayout()->createBlock('cmslayouts/adminhtml_menu')->toHtml();
    }

    /**
     * Return the version
     *
     * @access public
     * @param null
     * @return string
     */
    public function getVersion()
    {
        $config = Mage::app()->getConfig()->getModuleConfig('Yireo_CmsLayouts');
        return (string)$config->version;
    }
}
