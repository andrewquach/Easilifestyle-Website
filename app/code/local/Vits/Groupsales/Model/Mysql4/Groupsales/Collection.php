<?php

class Vits_Groupsales_Model_Mysql4_Groupsales_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupsales/groupsales');
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
	     
		    
		    foreach ($this as $commission)
		    {
			    if(isset($customer_ids[$commission->getCustomerId()]))
			    {
			    	$commission->setData('customer_name',$customer_ids[$commission->getCustomerId()]);
			    }
			    else 
			    {
			    	$commission->setData('customer_name','');
			    }
		    }
		    return $this;
    }
}