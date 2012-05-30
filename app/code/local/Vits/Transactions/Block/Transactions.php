<?php
class Vits_Transactions_Block_Transactions extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getTransactions()     
     { 
        if (!$this->hasData('transactions')) {
            $this->setData('transactions', Mage::registry('transactions'));
        }
        return $this->getData('transactions');
        
    }
}