<?php
class Vits_Ambassador_Block_Ambassador extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAmbassador()     
     { 
        if (!$this->hasData('ambassador')) {
            $this->setData('ambassador', Mage::registry('ambassador'));
        }
        return $this->getData('ambassador');
        
    }
	protected function __calculateBV1()
    {
    	$groupbv_collection = Mage::getModel('ambassador/groupbv')->getCollection();
    	$groupbv_collection->getSelect()->where('order_id =?','100000001');
    	$order = Mage::getModel('sales/order')->load(1);
    	echo $order->getState();
    	echo $order->getIncrementId();
    	//print_r($groupbv_collection->getData());
    }
	protected function __calculateBV($order_id)
    {
    		$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$bvTable = $resource->getTableName('groupbv');
			$sql = 'SELECT customer_id  , SUM(bv) FROM '.$bvTable.'
					 GROUP BY customer_id';
			$select = $read->query($sql);
			$data = $select->fetchAll();
			print_r($data);
    }
}
?>