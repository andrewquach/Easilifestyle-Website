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
 * Adminhtml customers list block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Vits_Ambassador_Block_Adminhtml_Transactions extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
    	$this->_controller = 'adminhtml_report_transactions';
        $this->_blockGroup = 'ambassador';
    	$customer_id = $this->getRequest()->getParam('id');
    	if($customer_id != null)
    	{
    		$customer = Mage::getModel('customer/customer')->load($customer_id);
    		$this->_headerText = Mage::helper('ambassador')->__('Commission Detail of '.$customer->getFirstname().' '.$customer->getLastname());
    	}
        else 
        {
        	 $this->_headerText = Mage::helper('ambassador')->__('Commission Detail');
        }
       
        parent::__construct();
    }
	protected function _prepareLayout()
    {
    	$customer = Mage::getModel('customer/session')->getCustomer()->getData();
    	if(isset($customer['entity_id']))
    	{
    		$back_location = Mage::getUrl('ambassador');
    	}
    	else 
    	{
    		$back_location = $this->getUrl('/customer',array());
    	}
    	$this->_removeButton('add');
    	$customer_id = $this->getRequest()->getParam('id');
    	if($customer_id != null)
    	{
    		Mage::getSingleton('customer/session')->getCustomer()->setData('child_balance_report',$customer_id);
    		$this->_addButton('back', array(
	         'label'     => $this->getBackButtonLabel(),
	         'onclick'   => 'setLocation(\''.$back_location.'\')',
	         'class'     => 'back',
	     	));
    	}
        return parent::_prepareLayout();
    }

}
