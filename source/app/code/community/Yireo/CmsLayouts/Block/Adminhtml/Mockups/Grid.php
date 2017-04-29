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


class Yireo_CmsLayouts_Block_Adminhtml_Mockups_Grid extends Yireo_CmsLayouts_Block_Adminhtml_Abstract_List_Grid
{
    protected $_gridId = 'cmslayoutsGrid';

    protected $_primaryKey = 'mockup_id';

    protected $_gridModel = 'cmslayouts/mockup';

    protected $_editAction = 'editMockup';

    protected $_saveAction = 'saveMockup';

    protected $_browseAction = 'mockups';

    protected $_enableAction = 'enableMockup';

    protected $_disableAction = 'disableMockup';

    protected $_deleteAction = 'deleteMockup';

    protected function _prepareColumns()
    {
        $this->_prependDefaultColumns();

        $this->addColumn('label', array(
            'header'=> Mage::helper('cmslayouts')->__('Label'),
            'index' => 'label',
            'type' => 'text',
        ));

        $this->addColumn('identifier', array(
            'header'=> Mage::helper('cmslayouts')->__('Identifier'),
            'index' => 'identifier',
            'type' => 'text',
        ));

        $this->addColumn('mockup_file', array(
            'header'=> Mage::helper('cmslayouts')->__('Mockup File'),
            'index' => 'mockup_file',
            'type' => 'text',
        ));

        $this->addColumn('is_active', array(
            'header'=> Mage::helper('cmslayouts')->__('Active'),
            'index' => 'is_active',
            'type' => 'text',
        ));

        $this->_appendDefaultColumns();

        return parent::_prepareColumns();
    }
}
