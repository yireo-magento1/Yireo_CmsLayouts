<?php
/**
 * CmsLayouts extension for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_CmsLayouts_Block_Adminhtml_Abstract_List extends Yireo_CmsLayouts_Block_Adminhtml_Abstract
{
    protected function prepareGridLayout($blockName)
    {
        $this->setChild('grid', $this->getLayout()
                ->createBlock($blockName, 'grid')
                ->setSaveParametersInSession(true)
        );

        $this->setChild('new_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('adminhtml')->__('New'),
                    'onclick' => 'setLocation(\''.$this->getNewUrl().'\')',
                    'class' => 'task'
                ))
        );
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getNewUrl()
    {
        return $this->getUrl('*/*/new');
    }

    public function getNewButtonHtml()
    {
        return $this->getChildHtml('new_button');
    }
}
