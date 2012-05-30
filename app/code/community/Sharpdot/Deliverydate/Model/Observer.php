<?php
/**
 * CustomSearch Observer
 *
 * @category    Exxex
 * @package     Essex_Customermod
 * @author      Sharpdotinc.com
 */
class Sharpdot_Deliverydate_Model_Observer
{				
	
	public function checkout_controller_onepage_save_shipping_method($observer)
	{
		$request = $observer->getEvent()->getRequest();
		$quote =  $observer->getEvent()->getQuote();
		
		$desiredArrivalDate = Mage::helper('deliverydate')->getFormatedDeliveryDateToSave($request->getPost('shipping_arrival_date', ''));
		$quote->setShippingArrivalDate($desiredArrivalDate);
		$quote->save();
		
		return $this;
	}
		
}