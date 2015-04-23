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

class Yireo_CmsLayouts_Block_Adminhtml_Mockup extends Yireo_CmsLayouts_Block_Adminhtml_Abstract_Edit
{
    protected $_saveAction = 'saveMockup';

    protected $_browseAction = 'mockups';

    protected $_deleteAction = 'deleteMockup';

    protected $_entityModel = 'cmslayouts/mockup';

    /*
     * Constructor method
     */
    public function _construct()
    {
        $this->setTemplate('cmslayouts/mockup.phtml');
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        $this->prepareEditLayout();

        $this->setChild('edit', $this->getLayout()
                ->createBlock('cmslayouts/adminhtml_mockup_edit', 'cmslayout.edit')
        );

        $this->setChild('select', $this->getLayout()
                ->createBlock('cmslayouts/adminhtml_mockup_select', 'cmslayout.select')
        );

        return parent::_prepareLayout();
    }
}
