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

class Yireo_CmsLayouts_Block_Adminhtml_Abstract_Edit extends Yireo_CmsLayouts_Block_Adminhtml_Abstract
{
    protected $_saveAction = 'save';

    protected $_browseAction = 'index';

    protected $_deleteAction = 'delete';

    protected function prepareEditLayout()
    {
        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('core')->__('Save'),
                    'onclick' => 'editForm.submit();',
                    'class' => 'save'
                ))
        );

        $this->setChild('apply_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('core')->__('Apply'),
                    'onclick' => 'editForm.submit();',
                    'class' => 'save'
                ))
        );

        if($this->getId() > 0) {
            $this->setChild('delete_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label' => Mage::helper('core')->__('Delete'),
                        'onclick' => 'setLocation(\''.$this->getBackUrl().'\')',
                        'class' => 'delete'
                    ))
            );
        }

        $this->setChild('back_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('core')->__('Back'),
                    'onclick' => 'setLocation(\''.$this->getBackUrl().'\')',
                    'class' => 'back'
                ))
        );
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/'.$this->_saveAction);
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/'.$this->_browseAction);
    }

    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/'.$this->_deleteAction, array($this->getPrimaryKey() => $this->getId()));
    }


    public function getPrimaryKey()
    {
        $resourceModel = Mage::getResourceModel($this->_entityModel);
        if (empty($resourceModel)) {
            throw new Exception('No resource-model found: '.$this->_entityModel);
        }

        return $resourceModel->getIdFieldName();
    }

    public function getId()
    {
        return $this->getEntity()->getData($this->getPrimaryKey());
    }

    public function getEntity()
    {
        $entity = Mage::getModel($this->_entityModel);

        $id = $this->getRequest()->getParam($this->getPrimaryKey());
        if(!empty($id)) {
            $entity->load($id);
        }

        return $entity;
    }
}
