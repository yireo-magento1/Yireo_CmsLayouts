<?php
/**
 * Yireo CmsLayouts for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        http://www.yireo.com/
 */

class Yireo_CmsLayouts_Block_Adminhtml_Mockup_Edit extends Yireo_CmsLayouts_Block_Adminhtml_Abstract_Edit
{
    protected $_saveAction = 'saveMockup';

    protected $_browseAction = 'mockups';

    protected $_deleteAction = 'deleteMockup';

    protected $_entityModel = 'cmslayouts/mockup';

    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('cmslayouts/mockup/edit.phtml');
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        if($this->getId() > 0) {

            $head = Mage::app()->getLayout()->getBlock('head');
            $head->addItem('skin_css', 'css/cmslayouts/default.css');
            $head->addItem('skin_css', 'css/cmslayouts/'.$this->getEntity()->getMockupFile().'.css');

            $mockupFileBlock = $this->getLayout()->createBlock('cmslayouts/adminhtml_mockup_file', 'cmslayout.mockup_file');
            $mockupFileBlock->setMockup($this->getEntity());

            $this->setChild('mockup_file', $mockupFileBlock);
        }

        return parent::_prepareLayout();
    }
}
