<?php

class Vits_Transactions_Block_Adminhtml_Transactions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'transactions';
        $this->_controller = 'adminhtml_transactions';
        
        $this->_updateButton('save', 'label', Mage::helper('transactions')->__('Save Transaction'));
        $this->_updateButton('delete', 'label', Mage::helper('transactions')->__('Delete Transaction'));
		

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('transactions_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'transactions_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'transactions_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
    	$customer_data=Mage::getResourceModel('customer/customer_collection')
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname');
      
	    $customer_ids = array();
	    foreach ($customer_data as $data):
	    $customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
	    endforeach;
	    
        if( Mage::registry('transactions_data') && Mage::registry('transactions_data')->getId() ) {
        	if(isset($customer_ids[Mage::registry('transactions_data')->getCustomerId()]))
        		$customer_name = $customer_ids[Mage::registry('transactions_data')->getCustomerId()];
        	else 
        		$customer_name = '';
            return Mage::helper('transactions')->__("Edit Transaction %s", $this->htmlEscape($customer_name).' ['.$this->htmlEscape(Mage::registry('transactions_data')->getTransactionsTime()).']');
        } else {
            return Mage::helper('transactions')->__('Add New Transaction');
        }
    }
}