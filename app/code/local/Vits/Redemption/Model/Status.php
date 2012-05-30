<?php

class Vits_Redemption_Model_Status extends Varien_Object
{
    const STATUS_PENDING	= 'pending';
    const STATUS_PROCESSING	= 'processing';
    const STATUS_COMPLETE	= 'complete';
    const STATUS_CANCEL	= 'cancel';

    static public function getOptionArray()
    {
        return array(
            self::STATUS_PENDING	 => Mage::helper('redemption')->__('Pending'),
		    self::STATUS_PROCESSING	 => Mage::helper('redemption')->__('Processing'),
		    self::STATUS_COMPLETE	 => Mage::helper('redemption')->__('Completed'),
		   self::STATUS_CANCEL	 => Mage::helper('redemption')->__('Cancelled'),
        );
    }
}