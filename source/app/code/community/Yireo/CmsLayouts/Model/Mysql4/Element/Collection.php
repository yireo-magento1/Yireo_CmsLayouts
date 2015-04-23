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
 * CmsLayouts item resource collection
 */
class Yireo_CmsLayouts_Model_Mysql4_Element_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('cmslayouts/element');
    }
}
