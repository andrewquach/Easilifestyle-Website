<?php
/**
 * @author Amasty
 */
class Amasty_Birth_Model_Observer
{
    public function send()
    {
        if (!Mage::getStoreConfig('ambirth/general/enabled'))
            return $this;
          
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('dob')
            ->addAttributeToFilter('dob', array('field_expr'=>"DATE_FORMAT(#?, '%m-%d')", 'eq'=>date('m-d')))             
            ->setPageSize(200)
            ->setCurPage(1)
            ->load();
            
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        
        foreach ($collection as $customer){
			$logCollection = Mage::getResourceModel('ambirth/log_collection')
				->addFieldToFilter('y', date('Y'))
				->addFieldToFilter('customer_id', $customer->getId());
				
			if ($logCollection->getSize() > 0){
				continue;
			}
		
            $store = Mage::app()->getStore($customer->getStoreId());
            $tpl = Mage::getModel('core/email_template');
            $tpl->setDesignConfig(array('area'=>'frontend', 'store'=>$store->getId()))
                ->sendTransactional(
                    Mage::getStoreConfig('ambirth/general/template', $store),
                    Mage::getStoreConfig('ambirth/general/identity', $store),
                    $customer->getEmail(),
                    $customer->getName(),
                    array(
                        'website_name'  => $store->getWebsite()->getName(),
                        'group_name'    => $store->getGroup()->getName(),
                        'store_name'    => $store->getName(), 
                        'coupon'        => $this->_createCoupon($store), 
                        'coupon_days'   => Mage::getStoreConfig('ambirth/general/coupon_days', $store), 
                        'customer_name' => $customer->getName(),
                    )
            );
    		$logModel = Mage::getModel('ambirth/log')
    			->setY(date('Y'))
    			->setCustomerId($customer->getId())
    			->setSentDate(date('Y-m-d H:i:s'));
		    $logModel->save();	
        }  
        $translate->setTranslateInline(true);  
        
			        
        return $this;
    }
    
    protected function _createCoupon($store)
    {
      	$couponData = array();
        $couponData['name']      = 'Birthday Coupon ' . date('Y-m-d');
        $couponData['is_active'] = 1;
        $couponData['website_ids'] = array(0 => $store->getWebsiteId());
        $couponData['coupon_code'] = strtoupper(uniqid()); 
        $couponData['uses_per_coupon'] = 1;
        $couponData['uses_per_customer'] = 1;
        $couponData['from_date'] = ''; //current date

        $days = Mage::getStoreConfig('ambirth/general/coupon_days', $store);
        //$date = Mage::helper('core')->formatDate(date('Y-m-d', time() + $days*24*3600));
        $date = date('Y-m-d', time() + $days*24*3600);
        $couponData['to_date'] = $date;
        
        $couponData['uses_per_customer'] = 1;
        $couponData['simple_action']   = Mage::getStoreConfig('ambirth/general/coupon_type', $store);
        $couponData['discount_amount'] = Mage::getStoreConfig('ambirth/general/coupon_amount', $store);
        $couponData['conditions'] = array(
            1 => array(
                'type'       => 'salesrule/rule_condition_combine',
                'aggregator' => 'all',
                'value'      => 1,
                'new_child'  =>'', 
            )
        );
        
        $couponData['actions'] = array(
            1 => array(
                'type'       => 'salesrule/rule_condition_product_combine',
                'aggregator' => 'all',
                'value'      => 1,
                'new_child'  =>'', 
            )
        );
        
        //create for all customer groups
        $couponData['customer_group_ids'] = array();
        
        $customerGroups = Mage::getResourceModel('customer/group_collection')
            ->load();

        $found = false;
        foreach ($customerGroups as $group) {
            if (0 == $group->getId()) {
                $found = true;
            }
            $couponData['customer_group_ids'][] = $group->getId();
        }
        if (!$found) {
            $couponData['customer_group_ids'][] = 0;
        }
        
        try { 
            Mage::getModel('salesrule/rule')
                ->loadPost($couponData)
                ->save();      
        } 
        catch (Exception $e){
            //print_r($e); exit;
            $couponData['coupon_code'] = '';   
        }
        return $couponData['coupon_code'];

    }        
    
}
