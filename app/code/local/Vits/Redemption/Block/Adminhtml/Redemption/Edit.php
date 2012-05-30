<?php

class Vits_Redemption_Block_Adminhtml_Redemption_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'redemption';
        $this->_controller = 'adminhtml_redemption';
        
        $this->_updateButton('save', 'label', Mage::helper('redemption')->__('Save Redemption Order'));
        $this->_updateButton('delete', 'label', Mage::helper('redemption')->__('Delete Redemption Order'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('redemption_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'redemption_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'redemption_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('redemption_data') && Mage::registry('redemption_data')->getId() ) {
            return Mage::helper('redemption')->__("Edit Redemption Order");
        } else {
            return Mage::helper('redemption')->__('Add Redemption Order');
        }
    }
}