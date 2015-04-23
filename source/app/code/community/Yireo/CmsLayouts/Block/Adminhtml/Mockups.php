<?php
/**
 * CmsLayouts extension for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_CmsLayouts_Block_Adminhtml_Mockups extends Yireo_CmsLayouts_Block_Adminhtml_Abstract_List
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('cmslayouts/mockups.phtml');
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        $this->prepareGridLayout('cmslayouts/adminhtml_mockups_grid');

        return parent::_prepareLayout();
    }

    public function getNewUrl()
    {
        return $this->getUrl('*/*/mockup');
    }
}
