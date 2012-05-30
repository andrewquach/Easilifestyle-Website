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
 * @package    Mage_Customer
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account controller
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Customer_AccountController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array('loginPost', 'create');

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        // a brute-force protection here would be nice

        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = $this->getRequest()->getActionName();
        if (!preg_match('/^(create|login|logoutSuccess|forgotpassword|forgotpasswordpost|confirm|confirmation)/i', $action)) {
            if (!$this->_getSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        }
    }

    /**
     * Default customer account page
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Account'));
        $this->renderLayout();
    }

    /**
     * Customer login form page
     */
    public function loginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $this->getResponse()->setHeader('Login-Required', 'true');
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    /**
     * Login post action
     */
    public function loginPostAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
                }
                catch (Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.',
                                Mage::helper('customer')->getEmailConfirmationUrl($login['username'])
                            );
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setUsername($login['username']);
                }
            } else {
                $session->addError($this->__('Login and password are required'));
            }
        }
        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl() ) {
            $session->setBeforeAuthUrl(Mage::helper('customer')->getAccountUrl());
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

    /**
     * Customer logout action
     */
    public function logoutAction()
    {
        $this->_getSession()->logout()
            ->setBeforeAuthUrl(Mage::getUrl());

        $this->_redirect('*/*/logoutSuccess');
    }

    /**
     * Logout success page
     */
    public function logoutSuccessAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Customer register form page
     */
    public function createAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*');
            return;
        }

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            foreach (Mage::getConfig()->getFieldset('customer_account') as $code=>$node) {
                if ($node->is('create') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }
			/**************USEZ***********************/
            $session = $this->_getSession();
            $random_key = $session->getRandomString(20);
            $customer->setData('random_key',$random_key);
			$parent_name = $this->getRequest()->getParam('parent_name');
			
			$parent_id = 0;
			
			if ($parent_name){
				$resource = Mage::getSingleton('core/resource');
				$read= $resource->getConnection('core_read');
				$customerTable = $resource->getTableName('customer_entity');
				$sel = $read->select()
					->from($customerTable,array('*'))
					->where('username=?', $parent_name);
				$parents = $read->fetchRow($sel);
				if(isset($parents['entity_id']))
				{
					$parent_id = $parents['entity_id']; 
				}
				
			}
			if ($parent_id == 0){
				$customer->setData('parent_id',$this->getRequest()->getParam('parent_id'));
            }
			else
				$customer->setData('parent_id',$parent_id);
				
				
			//$customer_model = Mage::getModel('customer/customer')->getCollection();
        	//$customer_model->getSelect()->where('nric = ?',$this->getRequest()->getParam('nric'));
        	//$customer_data = $customer_model->getData();
        	//if(count($customer_data)> 0)
        	//{
        		//Mage::throwException(Mage::helper('customer')->__('Distributor NRIC already exists'));
        	//}
        	
        	$customer->setData('nric',$this->getRequest()->getParam('nric'));
            
            /*************ENDUSEZ*********************/
            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }

            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                $address = Mage::getModel('customer/address')
                    ->setData($this->getRequest()->getPost())
                    ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                    ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false))
                    ->setId(null);
                $customer->addAddress($address);

                $errors = $address->validate();
                if (!is_array($errors)) {
                    $errors = array();
                }
            }

            try {
                $validationCustomer = $customer->validate();
                if (is_array($validationCustomer)) {
                    $errors = array_merge($validationCustomer, $errors);
                }
                $validationResult = count($errors) == 0;

                if (true === $validationResult) {
                    $customer->save();

                    if ($customer->isConfirmationRequired()) {
                        $customer->sendNewAccountEmail('confirmation', $this->_getSession()->getBeforeAuthUrl());
                        $this->_getSession()->addSuccess($this->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.',
                            Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())
                        ));
                        $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                        return;
                    }
                    else {
                        $this->_getSession()->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);
                        $this->_redirectSuccess($url);
                        return;
                    }
                } else {
                    $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            $this->_getSession()->addError($errorMessage);
                        }
                    }
                    else {
                        $this->_getSession()->addError($this->__('Invalid customer data'));
                    }
                }
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setCustomerFormData($this->getRequest()->getPost());
            }
            catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Can\'t save customer'));
            }
        }

        $this->_redirectError(Mage::getUrl('*/*/create', array('_secure'=>true)));
    }

    /**
     * Add welcome message and send new account email.
     * Returns success URL
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param bool $isJustConfirmed
     * @return string
     */
    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess($this->__('Thank you for registering with %s', Mage::app()->getStore()->getName()));

        $customer->sendNewAccountEmail($isJustConfirmed ? 'confirmed' : 'registered');

        $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }

    /**
     * Confirm customer account by id and confirmation key
     */
    public function confirmAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        try {
            $id      = $this->getRequest()->getParam('id', false);
            $key     = $this->getRequest()->getParam('key', false);
            $backUrl = $this->getRequest()->getParam('back_url', false);
            if (empty($id) || empty($key)) {
                throw new Exception($this->__('Bad request.'));
            }

            // load customer by id (try/catch in case if it throws exceptions)
            try {
                $customer = Mage::getModel('customer/customer')->load($id);
                if ((!$customer) || (!$customer->getId())) {
                    throw new Exception('Failed to load customer by id.');
                }
            }
            catch (Exception $e) {
                throw new Exception($this->__('Wrong customer account specified.'));
            }

            // check if it is inactive
            if ($customer->getConfirmation()) {
                if ($customer->getConfirmation() !== $key) {
                    throw new Exception($this->__('Wrong confirmation key.'));
                }

                // activate customer
                try {
                    $customer->setConfirmation(null);
                    $customer->save();
                }
                catch (Exception $e) {
                    throw new Exception($this->__('Failed to confirm customer account.'));
                }

                // log in and send greeting email, then die happy
                $this->_getSession()->setCustomerAsLoggedIn($customer);
                $successUrl = $this->_welcomeCustomer($customer, true);
                $this->_redirectSuccess($backUrl ? $backUrl : $successUrl);
                return;
            }

            // die happy
            $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
            return;
        }
        catch (Exception $e) {
            // die unhappy
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectError(Mage::getUrl('*/*/index', array('_secure'=>true)));
            return;
        }
    }

    /**
     * Send confirmation link to specified email
     */
    public function confirmationAction()
    {
        $customer = Mage::getModel('customer/customer');
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        // try to confirm by email
        $email = $this->getRequest()->getPost('email');
        if ($email) {
            try {
                $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
                if (!$customer->getId()) {
                    throw new Exception('');
                }
                if ($customer->getConfirmation()) {
                    $customer->sendNewAccountEmail('confirmation');
                    $this->_getSession()->addSuccess($this->__('Please, check your e-mail for confirmation key.'));
                }
                else {
                    $this->_getSession()->addSuccess($this->__('This e-mail does not require confirmation.'));
                }
                $this->_getSession()->setUsername($email);
                $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure' => true)));
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('Wrong email.'));
                $this->_redirectError(Mage::getUrl('*/*/*', array('email' => $email, '_secure' => true)));
            }
            return;
        }

        // output form
        $this->loadLayout();

        $this->getLayout()->getBlock('accountConfirmation')
            ->setEmail($this->getRequest()->getParam('email', $email));

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * Forgot customer password page
     */
    public function forgotPasswordAction()
    {
        $this->loadLayout();

        $this->getLayout()->getBlock('forgotPassword')->setEmailValue(
            $this->_getSession()->getForgottenEmail()
        );
        $this->_getSession()->unsForgottenEmail();

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * Forgot customer password action
     */
    public function forgotPasswordPostAction()
    {
        $email = $this->getRequest()->getPost('email');
        if ($email) {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->_getSession()->setForgottenEmail($email);
                $this->_getSession()->addError($this->__('Invalid email address'));
                $this->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
                return;
            }
            $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();

                    $this->_getSession()->addSuccess($this->__('A new password was sent'));

                    $this->getResponse()->setRedirect(Mage::getUrl('*/*'));
                    return;
                }
                catch (Exception $e){
                    $this->_getSession()->addError($e->getMessage());
                }
            }
            else {
                $this->_getSession()->addError($this->__('This email address was not found in our records'));
                $this->_getSession()->setForgottenEmail($email);
            }
        } else {
            $this->_getSession()->addError($this->__('Please enter your email.'));
            $this->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
            return;
        }

        $this->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
    }

    /**
     * Forgot customer account information page
     */
    public function editAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        if ($block = $this->getLayout()->getBlock('customer_edit')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $data = $this->_getSession()->getCustomerFormData(true);
        $customer = $this->_getSession()->getCustomer();
        if (!empty($data)) {
            $customer->addData($data);
        }
        if($this->getRequest()->getParam('changepass')==1){
            $customer->setChangePassword(1);
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Account Information'));
        $this->renderLayout();
    }

    /**
     * Change customer password action
     */
    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            $customer = Mage::getModel('customer/customer')
                ->setId($this->_getSession()->getCustomerId())
                ->setWebsiteId($this->_getSession()->getCustomer()->getWebsiteId());

            $fields = Mage::getConfig()->getFieldset('customer_account');
            foreach ($fields as $code=>$node) {
                if ($node->is('update') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }
            $errors = $customer->validate();
            if (!is_array($errors)) {
                $errors = array();
            }

            /**
             * we would like to preserver the existing group id
             */
            if ($this->_getSession()->getCustomerGroupId()) {
                $customer->setGroupId($this->_getSession()->getCustomerGroupId());
            }

            if ($this->getRequest()->getParam('change_password')) {
                $currPass = $this->getRequest()->getPost('current_password');
                $newPass  = $this->getRequest()->getPost('password');
                $confPass  = $this->getRequest()->getPost('confirmation');

                if (empty($currPass) || empty($newPass) || empty($confPass)) {
                    $errors[] = $this->__('Password fields can\'t be empty.');
                }

                if ($newPass != $confPass) {
                    $errors[] = $this->__('Please make sure your passwords match.');
                }

                $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                if (strpos($oldPass, ':')) {
                    list($_salt, $salt) = explode(':', $oldPass);
                } else {
                    $salt = false;
                }

                if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                    $customer->setPassword($newPass);
                } else {
                    $errors[] = $this->__('Invalid current password');
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }


            try {
                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('Account information was successfully saved'));

                $this->_redirect('customer/account');
                return;
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Can\'t save customer'));
            }
        }

        $this->_redirect('*/*/edit');
    }
    /*******************USEZ function*********************/
    public function affiliateAction()
    {
    	$this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
         //$data = $this->_getSession()->getCustomerFormData(true);
        $this->getLayout()->getBlock('head')->setTitle($this->__('Register as Affiliate Merchant'));

        $this->renderLayout();
    }
    public function affiliatePostAction()
    {
    	$customer = Mage::getModel('customer/session')->getCustomer();
    	$customer_data = $customer->getData();
    	
    	$customer_model = Mage::getModel('customer/customer')->load($customer_data['entity_id']);
    	$admin_model = Mage::getModel('admin/user')->setId(null);
    	$role_model = Mage::getModel('admin/role')->setId(null);
    	try 
    	{
	    	$admin_model->setData($customer_data);
	    	$admin_model->setData('username',$customer_data['email']);
	    	$admin_model->setCustomerId($customer_data['entity_id']);
	    	$password = $customer_data['random_key'];
	    	$admin_model->setData('password',$password);
	    	$admin_model->save();
	    	
	    	//Insert data row tuong ung voi Affiliate user vua tao vao bang admin_role
	    	// Lay thong tin cuar Role
	    	$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$adsTable = $resource->getTableName('admin_role');
			$select = $read->select()
			   ->from($adsTable,array('*'))
			    ->where('user_id=0 and role_name=\'Affiliate\'');
			$admin_role = $read->fetchRow($select);
			
			
			$role_model->setData('parent_id',$admin_role['role_id']);
			$role_model->setData('tree_level',$admin_role['tree_level']+1);
			$role_model->setData('sort_order',0);
			$role_model->setData('role_type','U');
			$role_model->setData('user_id',$admin_model->getId());
			$role_model->setData('role_name',$admin_model->getFirstname());
			$role_model->save();
			$customer_model->setData('is_affiliate','yes');
			$expiration_date = date('Y-m-d',strtotime('+ 1 years'));
			$customer_model->setData('expiration_date',$expiration_date);
			$customer_model->save();
			Mage::getSingleton('customer/session')->addSuccess('Congratulations! You have become a Affiliate Merchant with us!');
    	}
		catch (Exception $e)
		{
			Mage::getSingleton('customer/session')->addError('Sorry, we can\'t save your registration. Please check follow case and try again: <br /> 1. Your email have been using for another Affiliate Merchant. <br /> 2. Your Internet connection was interupted.');
            $this->_redirect('*/*/affiliate');
            return;
		}
		$this->_redirect('*/*/');
    }
    public function upgradeAction()
    {
    	$this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
         //$data = $this->_getSession()->getCustomerFormData(true);
        $this->getLayout()->getBlock('head')->setTitle($this->__('Upgrade to Distributor'));

        $this->renderLayout();
    	
    }
    public function upgradePostAction()
    {
    	if ($this->getRequest()->isPost()) {
            $customer = Mage::getModel('customer/customer')->load($this->_getSession()->getCustomerId());

            $fields = Mage::getConfig()->getFieldset('customer_account');
            foreach ($fields as $code=>$node) {
                if (($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }
			$data = $this->getRequest()->getPost();
			$customer->setData('gender',$data['gender']);
			$customer->setBankName($data['bank_name']);
			$customer->setBankCode($data['bank_code']);
			$customer->setAccountCode($data['account_code']);
			$customer->setBranchCode($data['branch_code']);
			
			
            $errors = $customer->validate();
            if (!is_array($errors)) {
                $errors = array();
            }

            /**
             * we would like to preserver the existing group id
             */
            if ($this->_getSession()->getCustomerGroupId()) {
                $customer->setGroupId($this->_getSession()->getCustomerGroupId());
            }
            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/upgrade');
                return $this;
            }
            try {
            	$customer_model = Mage::getModel('customer/customer')->getCollection();
            	$customer_model->getSelect()->where('username = ?',$data['username']);
            	$customer_data = $customer_model->getData();
            	if(count($customer_data)> 0)
            	{
            		Mage::throwException(Mage::helper('customer')->__('Distributor username already exists'));
            	}
				if(!preg_match("/^[a-zA-Z0-9]{6,10}$/",$data['username']))
            	{
            		Mage::throwException(Mage::helper('customer')->__('Distributor username must contain only 6-10 alphanumerics'));
            	}
				
            	$amount= Mage::getStoreConfig('admin/vipcustomer/upgradeamount');
            	if($amount > 0)
            	{
	                $customer->save();
	                //$this->_getSession()->setCustomer($customer)
	                   // ->addSuccess($this->__('Congratulations! You have been become an ambassador with us!'));
	
	                $this->_redirect('customer/account/paypalupgrade/id/'.$customer->getId().'/username/'.$data['username']);
	                return;
            	}
            	else 
            	{
            		$customer->setUserRole('ambassador');
					$customer->setUpgradation(date("jS F, Y"));
					$customer->setUsername($data['username']);
					$customer->save();
					/**USEZ Chuahoanthien**/
					/* Co the can them luu transaction hoac gui email cho admin, cho parent*/
					$this->_getSession()->setCustomer($customer)
			                    ->addSuccess($this->__('Congratulations! You have been become a Distributor with us!'));
					$this->_redirect('customer/account');
					return;
            	}
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Can\'t save customer'));
            }
        }

        $this->_redirect('*/*/upgrade');
    }
    public function paypalupgradeAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
    public function upgradesuccessAction()
    {
    	//var_dump($_POST);
    	//exit(0);
    	$response = $_POST;
    	$id_username = $_POST['invoice'];
   		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];
		$payer_status = $_POST['payer_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];
		$payer_email = $_POST['payer_email'];
		$arr = explode('-',$id_username);
		$invoice = $arr[0];
		$username = $arr[1];
		$customer = Mage::getModel('customer/customer')->load($invoice);
		$customer->setUserRole('ambassador');
		$customer->setUsername($username);
		$customer->save();
		/**USEZ Chuahoanthien**/
		/* Co the can them luu transaction hoac gui email cho admin, cho parent*/
		$this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('Congratulations! You have been become a Distributor with us!'));
		$this->_redirect('customer/account');
    }
    public function bvaccumulateAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
	public function glaccumulateAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
	public function balanceAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
	public function exportAccountbvCsvAction()
    {
        $fileName   = 'accountbv.csv';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountbv_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportAccountbvExcelAction()
    {
        $fileName   = 'accountbv.xml';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountbv_grid')
            ->getExcel($fileName);

        $this->_sendUploadResponse($fileName, $content);
    }
	public function exportAccountglCsvAction()
    {
        $fileName   = 'accountgl.csv';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountgl_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportAccountglExcelAction()
    {
        $fileName   = 'accountgl.xml';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountgl_grid')
            ->getExcel($fileName);

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
