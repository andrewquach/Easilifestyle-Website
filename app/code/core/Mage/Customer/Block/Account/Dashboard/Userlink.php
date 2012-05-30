<?php
class Mage_Customer_Block_Account_Dashboard_Userlink extends Mage_Core_Block_Template
{

    public function getCustomerName()
    {
        return Mage::getSingleton('customer/session')->getCustomer()->getName();
    }
    public function getUserlink()
    {
    	$customer = Mage::getSingleton('customer/session')->getCustomer();
    	$username = $customer->getUsername();
    	$userlink = Mage::getBaseUrl();
    	$userlink .= '?parent='.$username;
    	return $userlink;
    }

}
?>