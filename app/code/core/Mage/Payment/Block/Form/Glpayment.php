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
 * @package    Mage_Payment
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Mage_Payment_Block_Form_Glpayment extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payment/form/glpayment.phtml');
    }
	public function getItems()
    {
		return Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
    }

    public function getTotals()
    {
        return Mage::getSingleton('checkout/session')->getQuote()->getTotals();
    }
    public function getProduct(Mage_Sales_Model_Quote_Item $item)
    {
    	$product_id = $item->getProductId();
   		$product = Mage::getModel('catalog/product')->load($product_id);
   		return $product;
    }
   public function getUnitGL(Mage_Sales_Model_Quote_Item $item)
   {
   		return $this->getProduct($item)->getGlPrice();
   }
 	public function getRowTotalGL(Mage_Sales_Model_Quote_Item $item)
   {
   		return $this->getUnitGL($item)* $item->getQty();
   }
	public function getTotalGL()
   {
   		$total_gl = 0;
   		$items = $this->getItems();
   		foreach($items as $item):
   			$total_gl += $this->getRowTotalGL($item);
   		endforeach;
   		return $total_gl;
   }
	public function getCustomerGl()
   {
   		$customer_id = Mage::getModel('customer/session')->getCustomer()->getId();
        $collection = Mage::getResourceModel('customer/customer_collection');
        $collection->getSelect()->where('entity_id = ?',$customer_id);
	    $collection->addGlToResult();
	    $customer_gl = 0;
	    foreach ($collection as $data)
	    {
	   		$customer_gl = $data->getTotalGl();
	    	break;
	    }
	    return $customer_gl;
   }

  

}