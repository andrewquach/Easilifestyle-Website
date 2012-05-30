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
 * @package    Mage_Checkout
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Mage_Checkout_OnepageController extends Mage_Checkout_Controller_Action
{
    /**
     * @return Mage_Checkout_OnepageController
     */
    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->_preDispatchValidateCustomer()) {
            return $this;
        }

        return $this;
    }

    protected function _ajaxRedirectResponse()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

    protected function _expireAjax()
    {
        if (!$this->getOnepage()->getQuote()->hasItems()
            || $this->getOnepage()->getQuote()->getHasError()
            || $this->getOnepage()->getQuote()->getIsMultiShipping()) {
            $this->_ajaxRedirectResponse();
            exit;
        }
        $action = $this->getRequest()->getActionName();
        if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)
            && !in_array($action, array('index', 'progress'))) {
            $this->_ajaxRedirectResponse();
            exit;
        }
        Mage::getSingleton('core/translate_inline')->setIsAjaxRequest(true);
    }

    protected function _getShippingMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_shippingmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    protected function _getPaymentMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_paymentmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    protected function _getAdditionalHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_additional');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    /**
     * Enter description here...
     *
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /**
     * Checkout page
     */
    public function indexAction()
    {
        if (!Mage::helper('checkout')->canOnepageCheckout()) {
            Mage::getSingleton('checkout/session')->addError($this->__('Sorry, Onepage Checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl($this->getRequest()->getRequestUri());
        $this->getOnepage()->initCheckout();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
    }

    /**
     * Checkout status block
     */
    public function progressAction()
    {
        $this->_expireAjax();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function shippingMethodAction()
    {
        $this->_expireAjax();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function reviewAction()
    {
        $this->_expireAjax();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function successAction()
    {
        if (!$this->getOnepage()->getCheckout()->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        Mage::getSingleton('checkout/session')->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action');
        $this->renderLayout();
    }

    public function failureAction()
    {
        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }


    public function getAdditionalAction()
    {
        $this->getResponse()->setBody($this->_getAdditionalHtml());
    }

    /**
     * Address JSON
     */
    public function getAddressAction()
    {
        $this->_expireAjax();
        $addressId = $this->getRequest()->getParam('address', false);
        if ($addressId) {
            $address = $this->getOnepage()->getAddress($addressId);
            $this->getResponse()->setHeader('Content-type', 'application/x-json');
            $this->getResponse()->setBody($address->toJson());
        }
    }

    public function saveMethodAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $method = $this->getRequest()->getPost('method');
            $result = $this->getOnepage()->saveCheckoutMethod($method);
            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }

    /**
     * save checkout billing address
     */
    public function saveBillingAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
			
			$parent_id = 0;
			
			if(array_key_exists('parent_name', $data) && $data['parent_name']!= ""){
				$resource = Mage::getSingleton('core/resource');
				$read= $resource->getConnection('core_read');
				$customerTable = $resource->getTableName('customer_entity');
				$sel = $read->select()
					->from($customerTable,array('*'))
					->where('username=?', $data['parent_name']);
				$parents = $read->fetchRow($sel);
				if(isset($parents['entity_id']))
				{
					$parent_id = $parents['entity_id']; 
				}
				
			}
			
			if ($parent_id != 0){
				$data['parent_id'] = $parent_id;
            }
			
			$result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                /* check quote for virtual */
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                }
                elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {

                   $result['goto_section'] = 'shipping_method';

                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );

                    $result['allow_sections'] = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                }
                else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }

    public function saveShippingAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping', array());
            $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            $result = $this->getOnepage()->saveShipping($data, $customerAddressId);

            if (!isset($result['error'])) {
                $result['goto_section'] = 'shipping_method';
                $result['update_section'] = array(
                    'name' => 'shipping-method',
                    'html' => $this->_getShippingMethodsHtml()
                );
            }

//            $this->loadLayout('checkout_onepage_shippingMethod');
//            $result['shipping_methods_html'] = $this->getLayout()->getBlock('root')->toHtml();
//            $result['shipping_methods_html'] = $this->_getShippingMethodsHtml();

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }

    public function saveShippingMethodAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->getOnepage()->saveShippingMethod($data);
            /*
            $result will have erro data if shipping method is empty
            */
            if(!$result) {
                Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request'=>$this->getRequest(), 'quote'=>$this->getOnepage()->getQuote()));
                $this->getResponse()->setBody(Zend_Json::encode($result));

                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );

//                $result['payment_methods_html'] = $this->_getPaymentMethodsHtml();
            }
            $this->getResponse()->setBody(Zend_Json::encode($result));
        }

    }

    public function savePaymentAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('payment', array());
            /*
            * first to check payment information entered is correct or not
            */

            try {
                $result = $this->getOnepage()->savePayment($data);
            }
            catch (Mage_Payment_Exception $e) {
                if ($e->getFields()) {
                    $result['fields'] = $e->getFields();
                }
                $result['error'] = $e->getMessage();
            }
            catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            $redirectUrl = $this->getOnePage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
                $this->loadLayout('checkout_onepage_review');

                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->getLayout()->getBlock('root')->toHtml()
                );

//                $result['review_html'] = $this->getLayout()->getBlock('root')->toHtml();
            }

            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }

    public function saveOrderAction()
    {
        $this->_expireAjax();

        $result = array();
        try {
            if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all Terms and Conditions before placing the order.');
                    $this->getResponse()->setBody(Zend_Json::encode($result));
                    return;
                }
            }
            if ($data = $this->getRequest()->getPost('payment', false)) {
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }
            $this->getOnepage()->saveOrder();
            /******************USEZ****************************/
            $payment = $_POST['payment'];
            if ($payment['method']=='glpayment')
            {
            	$this->redirectGlCheckout();
            }
            /********************ENDUSEZ*******************************/
            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error']   = false;
        }
        catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
            $this->getOnepage()->getQuote()->save();
        }
        catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
            $this->getOnepage()->getQuote()->save();
        }

        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    /****************************USEZ function*********************************/
    public function redirectGlCheckout()
    {
    	$customer_id = Mage::getModel('customer/session')->getCustomer()->getId();
    	 //$this->getOnepage()->getCheckout()->setRedirectUrl(Mage::getBaseUrl().'paypal/standard/glcheckout/');
    	 $incrementId = $this->getOnepage()->getCheckout()->getLastOrderId();
    	 if($incrementId == NULL)
    	 	$incrementId = '000000000';
    	 $customer_gl = $this->getCustomerGl();
    	 $total_gl = $this->getTotalGL();
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
		$total = $total_gl;
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
		
    	$this->getOnepage()->updateGLOrder(); 
    }
    public function addNewGroupglUser($order_id = null,$groupgl_id = null,$gl_used = null)
    {
    	$group_gl_used_model = Mage::getModel('ambassador/groupglused')->setId(null);
    	$group_gl_used_model->setOrderId($order_id);
    	$group_gl_used_model->setGroupglId($groupgl_id);
    	$group_gl_used_model->setGlUsed($gl_used);
    	$group_gl_used_model->setCreateDate(date('Y-m-d'));
    	$group_gl_used_model->save();
    	    	
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
