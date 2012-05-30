<?php
class Vits_Orphanpool_Block_Orphanpool extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getOrphanpool()     
     { 
        if (!$this->hasData('orphanpool')) {
            $this->setData('orphanpool', Mage::registry('orphanpool'));
        }
        return $this->getData('orphanpool');
        
    }
}