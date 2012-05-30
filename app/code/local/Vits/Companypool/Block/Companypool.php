<?php
class Vits_Companypool_Block_Companypool extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getCompanypool()     
     { 
        if (!$this->hasData('companypool')) {
            $this->setData('companypool', Mage::registry('companypool'));
        }
        return $this->getData('companypool');
        
    }
}