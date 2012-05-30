<?php
class Vits_Commissions_Block_Commissions extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getCommissions()     
     { 
        if (!$this->hasData('commissions')) {
            $this->setData('commissions', Mage::registry('commissions'));
        }
        return $this->getData('commissions');
        
    }
}