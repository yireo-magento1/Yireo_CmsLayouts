<?php
/**
 * Yireo CmsLayouts for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * CmsLayouts model
 */
class Yireo_CmsLayouts_Model_Element extends Mage_Core_Model_Abstract
{
    protected $mockup = null;

    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('cmslayouts/element');
    }

    public function cleanAllElementsByMockupId($mockupId)
    {
        $elements = $this->getCollection()
            ->addFieldToFilter('mockup_id', $mockupId);

        if($elements->count() == 0) {
            return true;
        }

        foreach($elements as $element) {
            $element->delete();
        }

        return true;
    }


    public function getMockup()
    {
        if (empty($this->mockup)) {
            $this->mockup = Mage::getModel('cmslayouts/mockup')->load($this->getMockupId());
        }

        return $this->mockup;
    }
}
