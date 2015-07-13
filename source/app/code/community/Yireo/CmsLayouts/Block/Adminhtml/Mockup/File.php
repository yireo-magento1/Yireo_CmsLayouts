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

/**
 * Class Yireo_CmsLayouts_Block_Adminhtml_Mockup_File
 */
class Yireo_CmsLayouts_Block_Adminhtml_Mockup_File extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * @return string
     */
    public function _toHtml()
    {
        $mockup = $this->getMockup();
        $mockupFile = $mockup->getMockupFile();
        $this->setTemplate('cmslayouts/mockups/'.$mockupFile.'.phtml');

        $templateFile = $this->getTemplateFile();
        if (file_exists(Mage::getBaseDir('design') . DS . $templateFile) == false) {
            return false;
        }

        return parent::_toHtml();
    }

    /**
     * Method to generate a placeholder block
     *
     * @param $code
     */
    public function includeElementHtml($code)
    {
        if (empty($code)) {
            echo 'No code';
            return;
        }

        $element = $this->getLayout()->createBlock('cmslayouts/adminhtml_element', 'cmslayout.element.'.$code);

        $element->setMockup($this->getMockup());
        $element->setCode($code);
        $element->setElementData(Mage::helper('cmslayouts')->getElementDataByCode($this->getMockup(), $code));

        echo $element->toHtml();
    }
}
