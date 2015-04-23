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
 * CmsLayouts model
 */
class Yireo_CmsLayouts_Model_Mockup extends Mage_Core_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('cmslayouts/mockup');
    }

    public function _beforeSave()
    {
        $this->identifier = preg_replace('/([^a-z0-9]+)/', '-', strtolower($this->label));
    }

    public function _afterLoad()
    {
        $elements = $this->getData('elements');
        if (empty($elements)) {
            $elements = Mage::getModel('cmslayouts/element')->getCollection()
                ->addFieldToFilter('mockup_id', $this->getData('mockup_id'));
            $this->setData('elements', $elements);
        }
    }
}
