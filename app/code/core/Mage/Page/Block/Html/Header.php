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
 * @package    Mage_Page
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Html page block
 *
 * @category   Mage
 * @package    Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Page_Block_Html_Header extends Mage_Core_Block_Template
{
    public function _construct()
    {
        $this->setTemplate('page/html/header.phtml');
    }

    public function setLogo($logo_src, $logo_alt)
    {
        $this->setLogoSrc($logo_src);
        $this->setLogoAlt($logo_alt);
        return $this;
    }

    public function getLogoSrc()
    {
        if (empty($this->_data['logo_src'])) {
            $this->_data['logo_src'] = Mage::getStoreConfig('design/header/logo_src');
        }
        return $this->getSkinUrl($this->_data['logo_src']);
    }

    public function getLogoAlt()
    {
        if (empty($this->_data['logo_alt'])) {
            $this->_data['logo_alt'] = Mage::getStoreConfig('design/header/logo_alt');
        }
        return $this->_data['logo_alt'];
    }

    public function getWelcome()
    {
        if (empty($this->_data['welcome'])) {
            if (Mage::isInstalled() && Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->_data['welcome'] = $this->__('Welcome, %s!', $this->htmlEscape(Mage::getSingleton('customer/session')->getCustomer()->getName()));
            } else {
                $this->_data['welcome'] = $this->__('Welcome Guest!');
            }
        }

        return $this->_data['welcome'];
    }
	
	public function getHome()
    {
        return $this->__('Home');
    }
	
	public function getProducts()
    {
        return $this->__('Products');
    }
	
	public function getRedemption()
    {
        return $this->__('Redemption');
    }
	
	public function getNewsEvents()
    {
        return $this->__('News & Events');
    }
	
	public function getAboutUs()
    {
        return $this->__('About Us');
    }
	
	public function getContactUs()
    {
        return $this->__('Contact Us');
    }
	
	public function getFaq()
    {
        return $this->__('Faq');
    }
	
	public function getShoppingCart()
    {
        return $this->__('Shopping cart');
    }
	
	public function getNowInYourCart()
    {
        return $this->__('now in your cart');
    }
	
	public function getItem()
    {
        return $this->__('item(s)');
    }
	
    /**
     * ********************************
     * USEZ, ghi lai cookie cua Parent
     */
    public function setParentCookie()
    {
    	if(isset($_GET['parent']))
    	{
    		$parent_name = $_GET['parent'];
    		//$parents =Mage::getModel('customer/customer')->getCollection()->getSelect()->where('username=\'chienthang\'');
    		$resource = Mage::getSingleton('core/resource');
		    $read= $resource->getConnection('core_read');
		    $customerTable = $resource->getTableName('customer_entity');
		    $sel = $read->select()
			    ->from($customerTable,array('*'))
			    ->where('username=?', $parent_name);
			$parents = $read->fetchRow($sel); 
    		setcookie("user_parent", $parents['entity_id'], time()+3600,'/');
    	}
    }

}
