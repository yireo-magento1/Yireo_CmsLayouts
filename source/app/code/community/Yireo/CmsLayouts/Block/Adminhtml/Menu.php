<?php
/**
 * CmsLayouts extension for Magento
 *
 * @package     Yireo_CmsLayouts
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_CmsLayouts_Block_Adminhtml_Menu extends Mage_Core_Block_Template
{
    /**
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('cmslayouts/menu.phtml');
    }

    /**
     * Helper method to get a list of the menu-items
     */
    public function getMenuItems()
    {
        // Build the list of menu-items
        $items = array(
            array(
                'action' => 'mockups',
                'title' => 'Overview',
            ),
        );

        $url = Mage::getModel('adminhtml/url');
        $current_action = $this->getRequest()->getActionName();

        foreach($items as $index => $item) {

            if($item['action'] == $current_action) {
                $item['class'] = 'active';
            } else {
                $item['class'] = 'inactive';
            }

            $item['url'] = $url->getUrl('adminhtml/cmslayouts/'.$item['action']);

            $items[$index] = $item;
        }

        return $items;
    }
}
