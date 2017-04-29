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


class Yireo_CmsLayouts_Block_Adminhtml_Abstract_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_gridId = 'grid';

    protected $_primaryKey = 'id';

    protected $_defaultSort = null;

    protected $_gridModel = 'cmslayouts/mockup';

    protected $_editAction = 'edit';

    protected $_enableAction = 'enable';

    protected $_disableAction = 'disable';

    protected $_deleteAction = 'delete';

    public function __construct()
    {
        parent::__construct();

        $this->setId($this->_gridId);

        if (empty($this->_defaultSort)) {
            $this->setDefaultSort($this->_defaultSort);
        } else {
            $this->setDefaultSort($this->_primaryKey);
        }

        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel($this->_gridModel)->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    protected function _prependDefaultColumns()
    {
        $this->addColumn($this->_primaryKey, array(
            'header'=> Mage::helper('core')->__('ID'),
            'width' => '50px',
            'index' => $this->_primaryKey,
            'type' => 'number',
            'sortable' => false,
            'filter' => false,
        ));
    }

    protected function _appendDefaultColumns()
    {
        $this->addColumn('actions', array(
            'header'=> Mage::helper('core')->__('Action'),
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('core')->__('Edit'),
                    'url' => array('base' => '*/*/'.$this->_editAction),
                    'field' => $this->_primaryKey
                ),
                array(
                    'caption' => Mage::helper('core')->__('Delete'),
                    'url' => array('base' => '*/*/'.$this->_deleteAction),
                    'field' => $this->_primaryKey
                ),
                array(
                    'caption' => Mage::helper('core')->__('Disable'),
                    'url' => array('base' => '*/*/'.$this->_disableAction),
                    'field' => $this->_primaryKey
                ),
                array(
                    'caption' => Mage::helper('core')->__('Enable'),
                    'url' => array('base' => '*/*/'.$this->_enableAction),
                    'field' => $this->_primaryKey
                )
            ),
            'filter'    => false,
            'sortable'  => false,
        ));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField($this->_primaryKey);
        $this->getMassactionBlock()->setFormFieldName($this->_primaryKey);
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('core')->__('Delete'),
            'url'  => $this->getUrl('*/*/'.$this->_deleteAction),
        ));

        $this->getMassactionBlock()->addItem('disable', array(
            'label'=> Mage::helper('core')->__('Disable'),
            'url'  => $this->getUrl('*/*/'.$this->disableAction),
        ));

        $this->getMassactionBlock()->addItem('enable', array(
            'label'=> Mage::helper('core')->__('Enable'),
            'url'  => $this->getUrl('*/*/'.$this->enableAction),
        ));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/'.$this->_editAction, array($this->_primaryKey => $row->getData($this->_primaryKey)));
    }
}
