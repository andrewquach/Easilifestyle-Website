<?php 
class Mage_Customer_Block_Account_Upgrade extends Mage_Core_Block_Template
{

    public function getCustomerName()
    {
        return Mage::getSingleton('customer/session')->getCustomer()->getName();
    }
    public function getCustomer()
    {
    	return Mage::getModel('customer/session')->getCustomer();
    }

}
?>