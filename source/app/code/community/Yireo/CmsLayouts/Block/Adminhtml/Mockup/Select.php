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

class Yireo_CmsLayouts_Block_Adminhtml_Mockup_Select extends Mage_Adminhtml_Block_Widget_Container
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('cmslayouts/mockup/select.phtml');
        parent::_construct();
    }

    public function getMockupFileOptions()
    {
        $mockupFileOptions = array();

        $xmlFiles = Mage::helper('cmslayouts')->getMockupXmlFiles();
        foreach($xmlFiles as $xmlFile) {
            $xmlMetaData = Mage::helper('cmslayouts')->getMetaDataFromMockupXmlFile($xmlFile);

            if (empty($xmlMetaData)) {
                continue;
            }

            if ($xmlMetaData['active'] == 0) {
                continue;
            }

            $mockupFileOptions[] = $xmlMetaData;
        }

        return $mockupFileOptions;
    }

}
