<?php
/**
 * Yireo CmsLayouts for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        https://www.yireo.com/
 */

class Yireo_CmsLayouts_Block_Mockup extends Mage_Core_Block_Template
{
    protected $mockupId = 0;

    protected $mockup = 0;

    /**
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('cmslayouts/mockups/default.phtml');
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        // @todo: This is not working
        //$head = Mage::app()->getLayout()->getBlock('head');
        //$head->addItem('skin_css', 'css/cmslayouts/default.css');

        // @todo: This is not working
        //$update = Mage::app()->getLayout()->getUpdate();
        //$update->addHandle('cmslayouts_mockup');

        return parent::_prepareLayout();
    }

    public function setMockupId($mockupId)
    {
        $this->mockupId = $mockupId;
    }

    public function getMockupId()
    {
        if (empty($this->mockupId)) {
            $this->mockupId = $this->getRequest()->getParam('mockup_id');
        }

        return $this->mockupId;
    }

    public function getMockup()
    {
        if (!empty($this->mockup) && $this->mockup->getMockupId() > 0) {
            return $this->mockup;
        }

        $this->mockup = Mage::getModel('cmslayouts/mockup');

        if(!empty($this->mockupId)) {
            $this->mockup->load($this->mockupId);
        }

        $mockupFile = $this->mockup->getMockupFile();
        if (!empty($mockupFile)) {
            $template = 'cmslayouts/mockups/'.$mockupFile.'.phtml';
            $this->setTemplate($template);
        }

        return $this->mockup;
    }

    public function includeElementHtml($code)
    {
        if (empty($code)) {
            echo 'No code';
            return;
        }

        $element = $this->getLayout()->createBlock('cmslayouts/element', 'cmslayout.element.'.$code);

        $element->setMockup($this->getMockup());
        $element->setCode($code);
        $element->setElementData(Mage::helper('cmslayouts')->getElementDataByCode($this->getMockup(), $code));

        echo $element->toHtml();
    }

    protected function _toHtml()
    {
        $this->getMockup();

        $head = Mage::app()->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'css/cmslayouts/default.css');
        $head->addItem('skin_css', 'css/cmslayouts/'.$this->getMockup()->getMockupFile().'.css');

        return parent::_toHtml();
    }
}
