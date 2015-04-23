<?php
/**
 * Yireo CmsLayouts for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 * @link        https://www.yireo.com/
 */

class Yireo_CmsLayouts_Block_Adminhtml_Element extends Mage_Adminhtml_Block_Widget_Container
{
    protected $elementChildBlock = null;

    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('cmslayouts/element.phtml');
        parent::_construct();
    }

    protected function getElementChildBlock()
    {
        $template = 'cmslayouts/element/'.$this->getData('code').'.phtml';

        $this->elementChildBlock = $this->getLayout()->createBlock('core/template', 'cmslayout.element.'.$this->getCode());
        $this->elementChildBlock->setTemplate($template);

        $elementData = $this->getElementData();
        $this->elementChildBlock->setData('values', $elementData['value']);

        return $this->elementChildBlock;
    }

    public function getElementTemplate()
    {
        return $this->getElementChildBlock()->getTemplate();
    }


    public function getElementHtml()
    {
        return $this->getElementChildBlock()->toHtml();
    }

    public function getFormFields()
    {
        $mockupFile = $this->getMockup()->getMockupFile();
        $elementData = $this->getElementData();
        $code = $this->getCode();

        $mockupXmlFile = Mage::helper('cmslayouts')->getMockupXmlFile($mockupFile);
        $xmlMetaData = Mage::helper('cmslayouts')->getMetaDataFromMockupXmlFile($mockupXmlFile);

        if (empty($xmlMetaData['elements'][$code])) {
            return array();
        }

        $fields = $xmlMetaData['elements'][$code];
        foreach($fields as $fieldId => $field) {
            $fieldName = $field['name'];
            $field['id'] = $elementData['id'];

            $field['value'] = null;
            if(isset($elementData['value'][$fieldName])) {
                $field['value'] = $elementData['value'][$fieldName];
            }

            $fields[$fieldId] = $field;
        }

        return $fields;
    }
}
