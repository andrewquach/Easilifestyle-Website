<?php

class Mage_Paypal_Block_Standard_Glcheckout extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $standard = Mage::getModel('paypal/standard');

        $form = new Varien_Data_Form();
        $form->setAction($standard->getPaypalUrl())
            ->setId('paypal_standard_checkout')
            ->setName('paypal_standard_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($standard->getStandardGlCheckoutFormFields() as $field=>$value) {
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }
        $html = '<html><body>';
        $html.=$this->__('You have selected EL Point payment method. However, you must pay shipping and hanlding cost via paypal.<br />');
        $html.= $this->__('You will be redirected to Paypal in a few seconds.');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("paypal_standard_checkout").submit();</script>';
        $html.= '</body></html>';

        return $html;
    }
}