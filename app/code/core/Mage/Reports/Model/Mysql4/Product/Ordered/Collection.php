<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Reports
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Products Ordered (Bestsellers) Report collection
 *
 * @category   Mage
 * @package    Mage_Reports
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mage_Reports_Model_Mysql4_Product_Ordered_Collection extends Mage_Reports_Model_Mysql4_Product_Collection
{
    /*protected function _joinFields($from = '', $to = '')
    {
        $this->addAttributeToSelect('*')
            ->addOrderedQty($from, $to)
            ->setOrder('ordered_qty', 'desc');

        return $this;
    }*/
	
	/********************USEZ***********************************/
	protected function _joinFields($from = '', $to = '')
    {
    	$user = Mage::getSingleton('admin/session')->getUser()->getRole();
        $user_role =  $user['role_name'];
        $user_id = Mage::getSingleton('admin/session')->getUser()->getUserId();
        if($user_role == 'Affiliate')
        {
        	$this->addAttributeToSelect('*')
	            ->addOrderedQtySeller($from, $to,$user_id)
	            ->setOrder('ordered_qty', 'desc');
        }
        else 
        {
	        $this->addAttributeToSelect('*')
	            ->addOrderedQty($from, $to)
	            ->setOrder('ordered_qty', 'desc');
        }

        return $this;
    }
	
	/*******************ENDUSEZ****************************************/
    public function setDateRange($from, $to)
    {
        $this->_reset()
            ->_joinFields($from, $to);
        return $this;
    }

    public function setStoreIds($storeIds)
    {
        $storeId = array_pop($storeIds);
        $this->setStoreId($storeId);
        $this->addStoreFilter($storeId);
        return $this;
    }
}