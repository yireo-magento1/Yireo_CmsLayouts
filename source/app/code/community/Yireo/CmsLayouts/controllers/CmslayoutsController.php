<?php
/**
 * CmsLayouts extension for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (https://www.yireo.com/)
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
        //$this->_initAction()->_addContent($this->getLayout()->createBlock('cmslayouts/adminhtml_mockup'));
        $this->loadLayout();
        $this->renderLayout();
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
            $this->_redirect('adminhtml/cmslayouts/editMockup', array('mockup_id' => $mockup->getId()));
            return;
        }

        $mockup->setLabel($this->getRequest()->getParam('label'));
        $mockup->setIdentifier($this->getRequest()->getParam('identifier'));
        $mockup->setIsActive($this->getRequest()->getParam('is_active'));
        $mockup->save();

        // Save all elements for this mockup also
        $elements = $this->getRequest()->getParam('element');

        // Add any elements that are inserted in a weird way
        $params = $this->getRequest()->getParams();
        foreach ($params as $paramName => $paramValue) {
            if (preg_match('/^element__/', $paramName) == false) {
                continue;
            }

            $paramParts = explode('__', $paramName);
            if (count($paramParts) != 3) {
                continue;
            }

            $code = str_replace('_', '-', $paramParts[1]);
            $name = str_replace('_', '-', $paramParts[2]);

            $elements[$code][$name] = $paramValue;
        }

        if (!empty($elements)) {

            $elements = $this->handleFileUploads($elements);

            foreach($elements as $elementCode => $element) {
                $elementModel = Mage::getModel('cmslayouts/element');

                if (!empty($element['id'])) {
                    $elementModel->load($element['id']);
                }

                $elementModel->setMockupId($mockup->getMockupId());
                $elementModel->setCode($elementCode);
                $elementModel->setValue(json_encode($element));
                $elementModel->setIsActive(1);
                $elementModel->save();
            }
        }

        // Set a message
        Mage::getModel('adminhtml/session')->addSuccess($this->__('Saved mockup succesfully'));

        // Redirect
        $formRedirect = $this->getRequest()->getParam('form_redirect');

        if($formRedirect == 'current') {
            $this->_redirect('adminhtml/cmslayouts/editMockup', array('mockup_id' => $mockup->getId()));
            return;
        }

        $this->_redirect('adminhtml/cmslayouts/mockups');
    }

    protected function handleFileUploads($elements)
    {
        if (empty($_FILES['file']['name'])) {
            return false;
        }

        foreach($_FILES['file']['name'] as $elementName => $images) {
            if (empty($images)) {
                continue;
            }

            foreach($images as $imageFieldname => $image) {
                $imageData = array(
                    'name' => $_FILES['file']['name'][$elementName][$imageFieldname],
                    'type' => $_FILES['file']['type'][$elementName][$imageFieldname],
                    'tmp_name' => $_FILES['file']['tmp_name'][$elementName][$imageFieldname],
                    'error' => $_FILES['file']['error'][$elementName][$imageFieldname],
                    'size' => $_FILES['file']['size'][$elementName][$imageFieldname],
                );

                $uploadedFile = $this->uploadFile($imageData);
                if (!empty($uploadedFile)) {
                    $elements[$elementName]['image'] = $uploadedFile;
                }
            }
        }

        return $elements;
    }

    protected function uploadFile($imageData)
    {
        try {
            $uploader = new Varien_File_Uploader($imageData);
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            // @todo: Get this from the helper
            $path = Mage::getBaseDir('media') . DS . 'cmslayouts' . DS;
            $uploader->save($path, $imageData['name']);

        }catch(Exception $e) {
            // @todo: Stupidly enough this always throws "File was not uploaded"
        }

        return $imageData['name'];
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
        $data = $this->getRequest()->getParam('data');
        if(empty($data)) {
            return $this->returnAjaxResponse('No data found', 0);
        }

        $elementId = (int) $this->getRequest()->getParam('element_id');
        $element = $this->loadElement($elementId);
        if(empty($element)) {
            return $this->returnAjaxResponse('Unable to load element', 0);
        }

        $mockupId = (int)$this->getRequest()->getParam('mockup_id');
        if(empty($mockupId)) {
            return $this->returnAjaxResponse('No mockup given', 0);
        }

        $code = $this->getRequest()->getParam('code');
        if(empty($code)) {
            return $this->returnAjaxResponse('No code given', 0);
        }

        $element->setMockupId($mockupId);
        $element->setCode($code);
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
        // Initialize the rule
        $this->mockup = Mage::getModel('cmslayouts/mockup');

        $this->mockupId = $this->getRequest()->getParam('mockup_id');
        $this->mockup->load($this->mockupId);

        return $this->mockup;
    }

    protected function loadElement($elementId = null)
    {
        if (empty($elementId)) {
            $elementId = $this->getRequest()->getParam('element_id');
        }

        $this->elementId = $elementId;
        $this->element = Mage::getModel('cmslayouts/element');

        if(!empty($this->elementId)) {
            $this->element->load($this->elementId);
        }

        return $this->element;
    }

    protected function _isAllowed()
    {
        $aclResource = 'admin/cms/cmslayouts';

        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}
