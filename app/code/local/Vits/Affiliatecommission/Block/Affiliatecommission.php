<?php
class Vits_Affiliatecommission_Block_Affiliatecommission extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAffiliatecommission()     
     { 
        if (!$this->hasData('affiliatecommission')) {
            $this->setData('affiliatecommission', Mage::registry('affiliatecommission'));
        }
        return $this->getData('affiliatecommission');
        
    }
}