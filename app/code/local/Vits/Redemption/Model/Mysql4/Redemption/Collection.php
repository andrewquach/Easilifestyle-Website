<?php

class Vits_Redemption_Model_Mysql4_Redemption_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('redemption/redemption');
    }
	public function addCustomerName()
    {
    	$customer_data=Mage::getResourceModel('customer/customer_collection')
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname');
      
	    $customer_ids = array();
	    foreach ($customer_data as $data):
	    $customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
	    endforeach;
	     
		    
		    foreach ($this as $transaction)
		    {
			    if(isset($customer_ids[$transaction->getCustomerId()]))
			    {
			    	$transaction->setData('customer_name',$customer_ids[$transaction->getCustomerId()]);
			    }
			    else 
			    {
			    	$transaction->setData('customer_name','');
			    }
		    }
		    return $this;
    }
	public function addProductName()
    {
    	$customer_data=Mage::getModel('catalog/product')->getCollection()
        ->addAttributeToSelect('name');
      
	    $customer_ids = array();
	    foreach ($customer_data as $data):
	    $customer_ids[$data->getId()] = $data->getName();
	    endforeach;
		    foreach ($this as $transaction)
		    {
			    if(isset($customer_ids[$transaction->getProductId()]))
			    {
			    	$transaction->setData('product_name',$customer_ids[$transaction->getProductId()]);
			    }
			    else 
			    {
			    	$transaction->setData('product_name','');
			    }
		    }
		    return $this;
    }
}