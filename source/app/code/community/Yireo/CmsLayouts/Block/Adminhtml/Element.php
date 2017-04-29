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

class Yireo_CmsLayouts_Block_Adminhtml_Element extends Mage_Adminhtml_Block_Widget_Container
{
    protected $elementChildBlock = null;

    /**
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('cmslayouts/element.phtml');
        parent::_construct();
    }

    protected function getNewForm($code, $name, $value)
    {
        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form_'.$code,
            'method' => 'post',
        ));

        $values = array();
        $values[$name] = $value;
        $form->setValues($values);

        return $form;
    }

    public function getUploadForm($code, $name, $value)
    {
        $form = $this->getNewForm($code, $name, $value);

        $field = new Varien_Data_Form_Element_File(array(
          'label'     => $name . ' - ' . Mage::helper('cmslayouts')->__('New Image'),
          'required'  => false,
          'name'      => 'file['.$code.']['.$name.']',
        ));
        $field->setValue($value);

        $form->addElement($field);

        return $form;
    }

    public function getImageOptions()
    {
        $path = Mage::getBaseDir('media') . DS . 'cmslayouts' . DS;
        @mkdir($path);
        $files = glob($path.'*');

        $options = array('' => 'No image');

        foreach($files as $file) {
            $file = basename($file);
            $options[$file] = $file;
        }

        return $options;
    }

    public function getProductForm($code, $name, $value)
    {
        // https://github.com/Jarlssen/Jarlssen_ChooserWidget
        $form = $this->getNewForm($code, $name, $value);

        $productId = Mage::helper('cmslayouts')->getProductIdFromString($value);
        $product = Mage::getModel('catalog/product')->load((int) $productId);

        $chooserHelper = Mage::helper('jarlssen_chooser_widget/chooser');

        $fieldset = $form->addFieldset('product_fieldset', array('legend' => 'Product Selection'));

        $inputId = 'element__'.str_replace('-', '_', $code).'__'.$name;
        $inputName = 'element['.$code.']['.$name.']';

        $productConfig = array(
            'input_name'  => $inputName,
            'input_id'    => $inputId,
            'input_label' => $this->__('Product'),
            'button_text' => $this->__('Select Product...'),
            'required'    => true
        );

        $form->addValues(array($inputId => $value));

        $product->setData($inputId, $value);
        $chooserHelper->createProductChooser($product, $fieldset, $productConfig);

        return $form;
    }

    protected function getElementChildBlock()
    {
        $template = 'cmslayouts/element/'.$this->getData('code').'.phtml';

        $this->elementChildBlock = $this->getLayout()->createBlock('core/template', 'cmslayout.element.'.$this->getCode());
        $this->elementChildBlock->setData('area','frontend');
        $this->elementChildBlock->setTemplate($template);

        $templateFile = $this->elementChildBlock->getTemplateFile();
        if (file_exists(Mage::getBaseDir('design') . DS . $templateFile) == false) {
            return false;
        }

        $elementData = $this->getElementData();
        $this->elementChildBlock->setData('values', $elementData['value']);

        return $this->elementChildBlock;
    }

    public function getElementTemplate()
    {
        $elementChildBlock = $this->getElementChildBlock();
        if (empty($elementChildBlock)) {
            return 'Empty child block';
        }

        return $elementChildBlock->getTemplate();
    }

    public function getId()
    {
        $elementData = $this->getElementData();

        if(isset($elementData['id'])) {
            return $elementData['id'];
        }

        return 0;
    }

    public function getElementHtml()
    {
        $elementChildBlock = $this->getElementChildBlock();
        if (empty($elementChildBlock)) {
            return 'Empty child block';
        }

        try {
            return $elementChildBlock->toHtml();
        } catch(Exception $e) {
            return $e->getMessage();
        }
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
