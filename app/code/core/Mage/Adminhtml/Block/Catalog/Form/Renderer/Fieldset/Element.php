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
 * Catalog fieldset element renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{
    /**
     * Initialize block template
     */
    protected function _construct()
    {
        $this->setTemplate('catalog/form/renderer/fieldset/element.phtml');
    }

    /**
     * Retrieve data object related with form
     *
     * @return Mage_Catalog_Model_Product || Mage_Catalog_Model_Category
     */
    public function getDataObject()
    {
        return $this->getElement()->getForm()->getDataObject();
    }

    /**
     * Retireve associated with element attribute object
     *
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
    public function getAttribute()
    {
        return $this->getElement()->getEntityAttribute();
    }

    /**
     * Retrieve associated attribute code
     *
     * @return string
     */
    public function getAttributeCode()
    {
        return $this->getAttribute()->getAttributeCode();
    }

    /**
     * Check "Use default" checkbox display availability
     *
     * @return bool
     */
    public function canDisplayUseDefault()
    {
        if ($attribute = $this->getAttribute()) {
            if (!$attribute->isScopeGlobal()
                && $this->getDataObject()
                && $this->getDataObject()->getId()
                && $this->getDataObject()->getStoreId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check default value usage fact
     *
     * @return bool
     */
    public function usedDefault()
    {
        $devaultValue = $this->getDataObject()->getAttributeDefaultValue($this->getAttribute()->getAttributeCode());
        return is_null($devaultValue);
    }

    /**
     * Disable field in default value using case
     *
     * @return Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
     */
    public function checkFieldDisable()
    {
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }

    /**
     * Retrieve label of attribute scope
     *
     * GLOBAL | WEBSITE | STORE
     *
     * @return string
     */
    public function getScopeLabel()
    {
        $html = '';
        $attribute = $this->getElement()->getEntityAttribute();
        if (!$attribute || Mage::app()->isSingleStoreMode() || $attribute->getFrontendInput()=='gallery') {
            return $html;
        }
        if ($attribute->isScopeGlobal()) {
            $html.= '[GLOBAL]';
        }
        elseif ($attribute->isScopeWebsite()) {
            $html.= '[WEBSITE]';
        }
        elseif ($attribute->isScopeStore()) {
            $html.= '[STORE VIEW]';
        }

        return $html;
    }

    /**
     * Retrieve element label html
     *
     * @return string
     */
    public function getElementLabelHtml()
    {
        return $this->getElement()->getLabelHtml();
    }

    /**
     * Retrieve element html
     *
     * @return string
     */
    public function getElementHtml()
    {
        return $this->getElement()->getElementHtml();
    }
    /****************USEZ function***********************/
	public function getUserIdRole()
    {
        $user = Mage::getSingleton('admin/session')->getUser()->getRole();
        $user_role =  $user['role_name'];
        $user_id = Mage::getSingleton('admin/session')->getUser()->getUserId();
        return $user_id;
    }
	public function getUserRole()
    {
        $user = Mage::getSingleton('admin/session')->getUser()->getRole();
        $user_role =  $user['role_name'];
        $user_id = Mage::getSingleton('admin/session')->getUser()->getUserId();
        return $user_role;
    }
    public function checkAffiliateProduct()
    {
    	$user_role = $this->getUserRole();
    	if($user_role == 'Affiliate')
    	{
    		return true;
    	}
    	$product_id = $this->getRequest()->getParam('id');
    	if($product_id != null)
    	{
    		$product = Mage::getModel('catalog/product')->load($product_id);
    		$affiliate_id = $product->getSellerId();
    		if($affiliate_id != 0)
    		{
    			return true;
    		}
    		
    	}
    	return false;
    }
    public function  checkDisableCommission()
    {
    	$id = $this->getRequest()->getParam('id');
    	if($this->checkAffiliateProduct())
    	{
    		if($id != null && $this->getUserRole() == 'Affiliate')
    		{
    			return true;
    		}
    	}
    	return false;
    }
    public function getProductStatus()
    {
    	$id = $this->getRequest()->getParam('id');
    	if($this->checkAffiliateProduct())
    	{
    		if($id == null && $this->getUserRole() == 'Affiliate')
    		{
    			return true;
    		}
    	}
    	return false;
    }
}