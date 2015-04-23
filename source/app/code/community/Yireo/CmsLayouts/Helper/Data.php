<?php
/**
 * Yireo CmsLayouts for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * CmsLayouts helper
 */
class Yireo_CmsLayouts_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getMockupXmlFile($mockupFile)
    {
        $mockupFiles = $this->getMockupXmlFiles();
        foreach($mockupFiles as $mockupFile) {
            return $mockupFile;
        }

        return false;
    }

    public function getMockupXmlFiles()
    {
        $folder = BP.'/app/design/adminhtml/default/default/template/cmslayouts/mockups';
        $files = $this->getMockupXmlFilesFromFolder($folder);
        return $files;
    }

    public function getMockupXmlFilesFromFolder($folder)
    {
        return glob($folder.'/*.xml');
    }

    public function getMetaDataFromMockupXmlFile($xmlFile)
    {
        $phtmlFile = preg_replace('/\.xml$/', '.phtml', $xmlFile);
        $code = preg_replace('/\.xml$/', '', basename($xmlFile));

        $xml = simplexml_load_file($xmlFile);

        $elementArray = array();
        $elements = $xml->elements->children();
        foreach($elements as $element) {

            $elementId = (string)$element['id'];
            $elementArray[$elementId] = array();

            $fields = $element->fields->children();
            foreach($fields as $field) {
                $fieldArray = array();
                $fieldArray['name'] = (string) $field['name'];
                $fieldArray['type'] = (string) $field['type'];
                $fieldArray['label'] = (string) $field['label'];

                if ($fieldArray['type'] == 'select') {
                    $fieldArray['options'] = array();
                    foreach($field->children() as $option) {
                        $optionValue = (string) $option['value'];
                        $fieldArray['options'][$optionValue] = (string) $option;
                    }
                }

                $elementArray[$elementId][] = $fieldArray;
            }
        }

        $metaData = array(
            'code' => $code,
            'title' => (string)$xml->title,
            'xmlFile' => $xmlFile,
            'phtmlFile' => $phtmlFile,
            'active' => (int)$xml->active,
            'elements' => $elementArray,
        );

        return $metaData;
    }

    public function getElementDataByCode($mockup, $elementCode)
    {
        $elements = $mockup->getElements();

        if (empty($elements)) {
            return false;
        }

        foreach($elements as $element) {
            if($element->getCode() == $elementCode) {

                return array(
                    'id' => $element->getElementId(),
                    'code' => $element->getCode(),
                    'value' => json_decode($element->getValue(), true));
            }
        }

        return false;
    }
}
