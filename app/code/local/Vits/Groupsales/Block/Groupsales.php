<?php
class Vits_Groupsales_Block_Groupsales extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getGroupsales()     
     { 
        if (!$this->hasData('groupsales')) {
            $this->setData('groupsales', Mage::registry('groupsales'));
        }
        return $this->getData('groupsales');
        
    }
}