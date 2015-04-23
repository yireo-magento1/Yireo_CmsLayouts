<?php
/**
 * CmsLayouts extension for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * CmsLayouts admin controller
 *
 * @category   CmsLayouts
 * @package     Yireo_CmsLayouts
 */
class Yireo_CmsLayouts_CmslayoutsController extends Mage_Adminhtml_Controller_Action
{
    protected $mockupId = 0;

    protected $mockup = null;

    protected $elementId = 0;

    protected $element = null;

    /**
     * Common method
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/cmslayouts')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('CMS'), Mage::helper('adminhtml')->__('CMS'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('CMS Layouts'), Mage::helper('adminhtml')->__('CMS Layouts'))
        ;
        return $this;
    }

    /**
     * Default page
     */
    public function indexAction()
    {
        $this->mockupsAction();
    }

    /**
     * Mockups grid
     */
    public function mockupsAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('cmslayouts/adminhtml_mockups'))
            ->renderLayout();
    }

    /**
     * Mockup form
     */
    public function mockupAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('cmslayouts/adminhtml_mockup'))
            ->renderLayout();
    }

    public function editMockupAction()
    {
        $this->mockupAction();
    }

    public function saveMockupAction()
    {
        $mockup = $this->loadMockup();

        // Set the POST-data
        $mockupFile = $this->getRequest()->getParam('mockup_file');
        if (!empty($mockupFile)) {
            $mockup->setMockupFile($mockupFile);
            $mockup->save();
            $this->_redirect('adminhtml/cmslayouts/mockup', array('mockup_id' => $mockup->getId()));
            return;
        }

        $mockup->setLabel($this->getRequest()->getParam('label'));
        $mockup->setIdentifier($this->getRequest()->getParam('identifier'));
        $mockup->setIsActive($this->getRequest()->getParam('is_active'));
        $mockup->save();

        /*
        // Save all elements for this mockup also
        $elements = $this->getRequest()->getParam('element');

        $elementModel = Mage::getModel('cmslayouts/element');
        $elementModel->cleanAllElementsByMockupId($mockup->getMockupId());

        foreach($elements as $elementLabel => $element) {
            $elementModel = Mage::getModel('cmslayouts/element');
            $elementModel->setMockupId($mockup->getMockupId());
            $elementModel->setLabel($elementLabel);
            $elementModel->setValue(json_encode($element));
            $elementModel->setIsActive(1);
            $elementModel->save();
        }
        */

        // Set a message
        Mage::getModel('adminhtml/session')->addSuccess($this->__('Saved mockup succesfully'));

        // Redirect
        $this->_redirect('adminhtml/cmslayouts/mockups');
    }

    public function deleteMockupAction()
    {
        $mockup = $this->loadMockup();
        $mockup->delete();

        Mage::getModel('adminhtml/session')->addSuccess($this->__('Deleted mockup succesfully'));
        $this->_redirect('adminhtml/cmslayouts/mockups');
    }

    public function enableMockupAction()
    {
        $mockup = $this->loadMockup();
        $mockup->setIsActive(1)->save();

        Mage::getModel('adminhtml/session')->addSuccess($this->__('Enabled mockup succesfully'));
        $this->_redirect('adminhtml/cmslayouts/mockups');
    }

    public function disableMockupAction()
    {
        $mockup = $this->loadMockup();
        $mockup->setIsActive(0)->save();

        Mage::getModel('adminhtml/session')->addSuccess($this->__('Disabled mockup succesfully'));
        $this->_redirect('adminhtml/cmslayouts/mockups');
    }

    public function getElementHtmlByIdAction()
    {
        $elementId = $this->getRequest()->getParam('element_id');
        if(empty($elementId)) {
            return $this->returnAjaxResponse('No element ID found', 0);
        }

        $element = $this->loadElement($elementId);
        if(empty($element)) {
            return $this->returnAjaxResponse('Unable to load element', 0);
        }

        $code = $element->getCode();
        if (empty($code)) {
            echo 'No code found';
            exit;
        }

        $mockup = $element->getMockup();
        if (empty($mockup)) {
            echo 'No mockup found';
            exit;
        }

        $elementBlock = $this->getLayout()->createBlock('cmslayouts/adminhtml_element', 'cmslayout.element.'.$code);

        $elementBlock->setMockup($element->getMockup());
        $elementBlock->setCode($code);
        $elementBlock->setElementData(Mage::helper('cmslayouts')->getElementDataByCode($element->getMockup(), $code));

        echo $elementBlock->toHtml();
    }

    public function getElementByIdThroughAjaxAction()
    {
        $elementId = $this->getRequest()->getParam('element_id');
        if(empty($elementId)) {
            return $this->returnAjaxResponse('No element ID found', 0);
        }

        $element = $this->loadElement($elementId);
        if(empty($element)) {
            return $this->returnAjaxResponse('Unable to load element', 0);
        }

        $jsonData = json_encode($element->getData('value'));
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }

    public function saveElementThroughAjaxAction()
    {
        $elementId = $this->getRequest()->getParam('element_id');
        if(empty($elementId)) {
            return $this->returnAjaxResponse('No element ID found', 0);
        }

        $data = $this->getRequest()->getParam('data');
        if(empty($data)) {
            return $this->returnAjaxResponse('No data found', 0);
        }

        $element = $this->loadElement($elementId);
        if(empty($element)) {
            return $this->returnAjaxResponse('Unable to load element', 0);
        }

        $element->setValue(json_encode($data));
        $element->save();

        $this->returnAjaxResponse('Successfully saved product', 1);
    }

    protected function returnAjaxResponse($message, $status)
    {
        $data = array('message' => $message, 'status' => $status);
        $jsonData = json_encode($data);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }

    protected function loadMockup()
    {
        $this->mockupId = $this->getRequest()->getParam('mockup_id');

        if(empty($this->mockupId)) {
            return $this->_redirect('adminhtml/cmslayouts/mockups');
        }

        // Initialize the rule
        $this->mockup = Mage::getModel('cmslayouts/mockup');
        if(empty($this->mockupId)) {
            return $this->_redirect('adminhtml/cmslayouts/mockups');
        }

        $this->mockup->load($this->mockupId);

        return $this->mockup;
    }

    protected function loadElement($elementId = null)
    {
        if (empty($elementId)) {
            $elementId = $this->getRequest()->getParam('element_id');
        }

        if(empty($elementId)) {
            return false;
        }

        $this->elementId = $elementId;

        // Initialize the rule
        $this->element = Mage::getModel('cmslayouts/element');
        if(empty($this->elementId)) {
            return false;
        }

        $this->element->load($this->elementId);

        return $this->element;
    }
}