<?php
class Vits_Redemption_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }
    public function orderAction()
    {
    	$data = $this->getRequest()->getPost();
    	//print_r($data);
    	$this->loadLayout();     
		$this->renderLayout();
		
		//print_r($this->getProduct()->getTypeInstance(true)->getSelectedAttributesInfo($this->getProduct()));
    }
    public function successAction()
    {
    	$this->loadLayout();     
		$this->renderLayout();
    }
	
	private function getQty()
    {
    	$data = $this->getRequest()->getPost();
    	if(isset($data['qty']) && $data['qty'] != '' )
    		$qty = $data['qty'];
    	else
    		$qty = 1;
    	return $qty;
    }
	
	private function getProduct()
    {
    	$product_id = $this->getRequest()->getParam('id');
    	$product = Mage::getModel('catalog/product')->load($product_id);
    	return $product;
    }
	
	private function getTotal()
    {
    	$qty = $this->getQty();
    	$gl_price = $this->getProduct()->getGlPrice();
    	
    	return $qty*$gl_price;
    	
    }
	
	public function placeorderAction()
    {
		$customer_id = Mage::getModel('customer/session')->getCustomer()->getId();
    	$data = $this->getRequest()->getPost();
    	$redemption_id = $this->saveRedemptionOrder($data['product_id'],$this->getQty(),$this->getProduct()->getGlPrice(),$this->getTotal(),$data['product_name']);
    	$incrementId = $redemption_id;
    	$total = $data['total'];
    	$today = date('Y-m-d');
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$glTable = $resource->getTableName('groupgl');
		$sql = 'SELECT id FROM '.$glTable.'
				WHERE gl_expiration_date >= \''.$today.'\' AND status <> \'used\' 
				 AND customer_id = '.$customer_id.' ORDER BY gl_expiration_date ASC';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		$groupgl_model = Mage::getModel('ambassador/groupgl');
		
		foreach ($data as $k=>$v)
		{
			if($total <= 0)
			{
				break;
			}
			$groupgl= $groupgl_model->load($v['id']);
			$gl = $groupgl->getGl();
			$gl_used = $groupgl->getGlUsed();
			$gl_having = $gl-$gl_used;
			if(($total - $gl_having) > 0)
			{
				
				$groupgl->setGlUsed($gl_having + $gl_used);
				$groupgl->setStatus('used');
				$groupgl->save();
				$this->addNewGroupglUser($incrementId,$v['id'],$gl_having);
				$total = $total - $gl_having;
				
			}
			elseif (($total - $gl_having) == 0)
			{
				
				$groupgl->setGlUsed($gl_having + $gl_used);
				$groupgl->setStatus('used');
				$groupgl->save();
				$this->addNewGroupglUser($incrementId,$v['id'],$gl_having);
				$total = $total - $gl_having;
				
			}
			else 
			{
				
				$groupgl->setGlUsed($total +  $gl_used);
				$groupgl->setStatus('using');
				$groupgl->save();
				$this->addNewGroupglUser($incrementId,$v['id'],$total);
				$total = $total - $gl_having;
				
			}
			
		}
		$this->_redirect('*/*/success');
    }
    public function saveRedemptionOrder($product_id=null,$qty=null,$gl_price=null,$total_gl=null,$product_name=null)
    {
		$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname');
		$customer_ids = array();
		foreach ($customer_data as $data):
			$customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
		endforeach;
	
    	$customer_id = Mage::getModel('customer/session')->getCustomer()->getId();
    	$redemption = Mage::getModel('redemption/redemption')->setId(null);
    	$redemption->setData('status','pending');
    	$redemption->setData('product_id',$product_id);
		$redemption->setData('product_name',$product_name);
    	$redemption->setData('customer_id',$customer_id);
		$redemption->setName($customer_ids[$customer_id]);
    	$redemption->setData('qty',$qty);
    	$redemption->setData('gl_price',$gl_price);
    	$redemption->setData('total_gl',$total_gl);
    	$redemption->setData('created_time',date('Y-m-d H:i:s'));
    	$redemption->setData('update_time',date('Y-m-d H:i:s'));
    	//$redemption->setData('note','pending');
    	$redemption->save();
		
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$glTable = $resource->getTableName('cataloginventory_stock_item');
		$sql = 'UPDATE '.$glTable.' SET qty = qty - '.$qty.'
				WHERE product_id = '.$product_id;
		//var_dump($sql);
		//exit();	
		$select = $read->query($sql);
		
    	return $redemption->getId();
    }
 	public function addNewGroupglUser($order_id = null,$groupgl_id = null,$gl_used = null)
    {
    	$group_gl_used_model = Mage::getModel('ambassador/groupglused')->setId(null);
    	$group_gl_used_model->setOrderId('Redemption-'.$order_id);
    	$group_gl_used_model->setGroupglId($groupgl_id);
    	$group_gl_used_model->setGlUsed($gl_used);
    	$group_gl_used_model->setCreateDate(date('Y-m-d'));
    	$group_gl_used_model->save();
    	    	
    }
}