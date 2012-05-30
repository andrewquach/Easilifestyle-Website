<?php

class Vits_Transactions_Model_Mysql4_Transactions_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('transactions/transactions');
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
}