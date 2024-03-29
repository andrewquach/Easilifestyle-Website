<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Customer_Edit_Tab_Account extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
    }
    /********************USEZ*********************/
	public function checkOrphanUser($id)
	{
		$customer = Mage::getModel('customer/customer')->load($id);
		if($customer->getParentId())
			return 'disabled';
		return false;
	}
/******************ENDUSEZ****************************/
    public function initForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_account');
        $form->setFieldNameSuffix('account');

        $customer = Mage::registry('current_customer');

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend'=>Mage::helper('customer')->__('Account Information'))
        );
        /************USEZ*************************/
		$current_id = $this->getRequest()->getParam('id');
		$customer_data=Mage::getResourceModel('customer/customer_collection')
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname');
      
	    $customer_ids = array();
	    $customer_ids[0] = '--Select--';
	    foreach ($customer_data as $data):
	    if(($data->getId() != $current_id) && ($data->getUserRole() != 'customer'))
	    {
	    	$customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
	    }
	    endforeach;
		
		$customer_model = Mage::getModel('customer/customer')->load($current_id);
		$customer_role = $customer_model->getUserRole();
		$is_aff = $customer_model->getIsAffiliate();
		   
      $fieldset->addField('parent_id', 'select', array(
          'label'     => Mage::helper('customer')->__('Parent'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'parent_id',
      	  'disabled'  => $this->checkOrphanUser($current_id),
      	  'values' 	=> $customer_ids
      ));
      $fieldset->addField('nric', 'text', array(
          'label'     => Mage::helper('customer')->__('Distributor NRIC/Passport'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'nric'
      ));

      if($customer_role != 'customer'){
      $fieldset->addField('username', 'text', array(
          'label'     => Mage::helper('customer')->__('Distributor ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'username'
      ));
       $fieldset->addField('user_role', 'select', array(
          'label'     => Mage::helper('customer')->__('User Role'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'user_role',
      	  'values' 	=> array(
       						'customer'=>'Member',
       						'ambassador'=>'Distributor',
       						'seniorambassador'=>'Ambassador',						
       ),
      ));
	  
	  // $dateFormatIso = Mage::app()->getLocale() ->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
	  // $fieldset->addField('upgradation', 'date', array(
          // 'label'     => Mage::helper('customer')->__('Upgradation Date'),
          // 'class'     => 'required-entry',
          // 'required'  => true,
          // 'name'      => 'upgradation',
		  // 'format' => $dateFormatIso,
		  // 'image' => $this->getSkinUrl('images/grid-cal.gif')
      // ));	  
	  // $form->getElement('upgradation')->setDisabled('disabled');
       // */
	   $fieldset->addField('gender', 'select', array(
          'label'     => Mage::helper('customer')->__('Gender'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'gender',
      	  'values' 	=> array(
       						''=>'--Select--',
       						'Male'=>'Male',
       						'Female'=>'Female',						
       ),
      ));
      $fieldset->addField('is_affiliate', 'select', array(
          'label'     => Mage::helper('customer')->__('Is Affiliate'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'is_affiliate',
      	  'values' 	=> array(
       						'no'=>'No',
       						'yes'=>'Yes',
       ),
      ));
      }
      if($is_aff == 'yes'){
      $fieldset->addField('expiration_date', 'text', array(
          'label'     => Mage::helper('customer')->__('Affiliate Expiration'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'expiration_date',
      	  'style'	  => 'width:110px'
      ));
      }
      /*****************ENDUSEZ*******************************/
        $this->_setFieldset($customer->getAttributes(), $fieldset);

        if ($customer->getId()) {
            $form->getElement('website_id')->setDisabled('disabled');
            $form->getElement('created_in')->setDisabled('disabled');
        } else {
            $fieldset->removeField('created_in');
        }

        $form->getElement('email')->addClass('validate-email');

//        if (Mage::app()->isSingleStoreMode()) {
//            $fieldset->removeField('website_id');
//            $fieldset->addField('website_id', 'hidden', array(
//                'name'      => 'website_id'
//            ));
//            $customer->setWebsiteId(Mage::app()->getStore(true)->getWebsiteId());
//        }

        if ($customer->getId()) {
            if (!$customer->isReadonly()) {
                // add password management fieldset
                $newFieldset = $form->addFieldset(
                    'password_fieldset',
                    array('legend'=>Mage::helper('customer')->__('Password Management'))
                );
                // New customer password
                $field = $newFieldset->addField('new_password', 'text',
                    array(
                        'label' => Mage::helper('customer')->__('New Password'),
                        'name'  => 'new_password',
                        'class' => 'validate-new-password'
                    )
                );
                $field->setRenderer($this->getLayout()->createBlock('adminhtml/customer_edit_renderer_newpass'));

                // prepare customer confirmation control (only for existing customers)
                $confirmationKey = $customer->getConfirmation();
                if ($confirmationKey || $customer->isConfirmationRequired()) {
                    $confirmationAttribute = $customer->getAttribute('confirmation');
                    if (!$confirmationKey) {
                        $confirmationKey = $customer->getRandomConfirmationKey();
                    }
                    $element = $fieldset->addField('confirmation', 'select', array(
                        'name'  => 'confirmation',
                        'label' => Mage::helper('customer')->__($confirmationAttribute->getFrontendLabel()),
                    ))->setEntityAttribute($confirmationAttribute)
                        ->setValues(array('' => 'Confirmed', $confirmationKey => 'Not confirmed'));

                    // prepare send welcome email checkbox, if customer is not confirmed
                    // no need to add it, if website id is empty
                    if ($customer->getConfirmation() && $customer->getWebsiteId()) {
                        $fieldset->addField('sendemail', 'checkbox', array(
                            'name'  => 'sendemail',
                            'label' => Mage::helper('customer')->__('Send Welcome Email after Confirmation')
                        ));
                    }
                }
            }
        }
        else {
            $newFieldset = $form->addFieldset(
                'password_fieldset',
                array('legend'=>Mage::helper('customer')->__('Password Management'))
            );
            $field = $newFieldset->addField('password', 'text',
                array(
                    'label' => Mage::helper('customer')->__('Password'),
                    'class' => 'input-text required-entry validate-password',
                    'name'  => 'password',
                    'required' => true
                )
            );
            $field->setRenderer($this->getLayout()->createBlock('adminhtml/customer_edit_renderer_newpass'));

            // prepare send welcome email checkbox
            $fieldset->addField('sendemail', 'checkbox', array(
                'label' => Mage::helper('customer')->__('Send welcome email'),
                'name'  => 'sendemail',
                'id'    => 'sendemail',
            ));
        }

        // make sendemail disabled, if website_id has empty value
        if ($sendemail = $form->getElement('sendemail')) {
            $prefix = $form->getHtmlIdPrefix();
            $sendemail->setAfterElementHtml(
                '<script type="text/javascript">'
                . "
                $('{$prefix}website_id').disableSendemail = function() {
                    $('{$prefix}sendemail').disabled = ('' == this.value || '0' == this.value);
                }.bind($('{$prefix}website_id'));
                Event.observe('{$prefix}website_id', 'click', $('{$prefix}website_id').disableSendemail);
                $('{$prefix}website_id').disableSendemail();
                "
                . '</script>'
            );
        }
        if ($customer->isReadonly()) {
        	
            foreach ($customer->getAttributes() as $attribute) {
                $element = $form->getElement($attribute->getAttributeCode());
                if ($element) {
                    $element->setReadonly(true, true);
                }
            }
        }

        $form->setValues($customer->getData());
        $this->setForm($form);
        return $this;
    }
}
