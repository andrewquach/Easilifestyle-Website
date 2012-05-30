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
 * @package    Mage_Customer
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Mage_Customer_Block_Account_Dashboard_Hello extends Mage_Core_Block_Template
{

    public function getCustomerName()
    {
        return Mage::getSingleton('customer/session')->getCustomer()->getName();
    }
    public function checkUpgrade()
    {
    	$customer = Mage::getModel('customer/session')->getCustomer();
    	if($customer->getUserRole() != 'customer')
    	{
    		return false;
    	}
    	else 
    	{
	    	$colection = Mage::getModel('ambassador/groupbv')->getCollection();
	    	$colection->getSelect()->where('customer_id = ?',$customer->getId());
	    	$customer_bv = $colection->getData();
	    	$total_bv = 0;
	    	foreach ($customer_bv as $k=>$v):
	    		if(isset($v['bv']))
	    		{
	    			$total_bv += $v['bv'];
	    		}
	    	endforeach;
	    	if($total_bv >= Mage::getStoreConfig('admin/vipcustomer/bvrequire'))
	    	{
	    		return true;
	    	}
    	}
    	return false;
    }
    
    public function checkDistributorUpgrade()
    {
    	$customer = Mage::getModel('customer/session')->getCustomer();
    	if($customer->getUserRole() != 'customer')
    	{
    		return 0.5;
    	}
    	else 
    	{
	    	$colection = Mage::getModel('ambassador/groupbv')->getCollection();
	    	$colection->getSelect()->where('customer_id = ?',$customer->getId());
	    	$customer_bv = $colection->getData();
	    	$total_bv = 0;
	    	foreach ($customer_bv as $k=>$v):
	    		if(isset($v['bv']))
	    		{
	    			$total_bv += $v['bv'];
	    		}
	    	endforeach;
	    	//if($total_bv >= Mage::getStoreConfig('admin/vipcustomer/bvrequire'))
	    	//{
	    		return (Mage::getStoreConfig('admin/vipcustomer/bvrequire') - $total_bv);
	    	//}
    	}
    	return 1.5;
    }

}
