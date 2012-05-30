<?php
class Vits_Redemption_Block_Redemption extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getRedemption()     
     { 
        if (!$this->hasData('redemption')) {
            $this->setData('redemption', Mage::registry('redemption'));
        }
        return $this->getData('redemption');
        
    }
    public function getProduct()
    {
    	$product_id = $this->getRequest()->getParam('id');
    	$product = Mage::getModel('catalog/product')->load($product_id);
    	return $product;
    }	
	
	public function getProductFullName()
    {
    	$productname = $this->getProduct()->getName();
		$data = $this->getRequest()->getPost();		

		
		if (!isset($data['super_attribute']))
			return $productname;
			
		$arr = $data['super_attribute'];
		$att = "";
		$val = "";
		
		foreach ($arr as $key => $value){
			$att = $key;
			$val = $value;
		}
		
		
    	$attribute = "";
		$value = "";
		
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');

		$readresult = $write->query("SELECT frontend_label FROM `usez_eav_attribute` WHERE attribute_id = $att");
		while ($row = $readresult->fetch() ) {
			$attribute .= $row['frontend_label'];
		}
		
		$readresult = $write->query("SELECT value FROM `usez_eav_attribute_option_value` WHERE option_id = $val");
		while ($row = $readresult->fetch() ) {
			$value .= $row['value'];
		}
		return $productname." - ".$attribute.":".$value;

    }
	
    public function getQty()
    {
    	$data = $this->getRequest()->getPost();
    	if(isset($data['qty']) && $data['qty'] != '' )
    		$qty = $data['qty'];
    	else
    		$qty = 1;
    	return $qty;
    }
    public function getTotal()
    {
    	$qty = $this->getQty();
    	$gl_price = $this->getProduct()->getGlPrice();
    	
    	return $qty*$gl_price;
    	
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