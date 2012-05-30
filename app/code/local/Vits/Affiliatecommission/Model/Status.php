<?php

class Vits_Affiliatecommission_Model_Status extends Varien_Object
{
	const STATUS_PENDING	= 'pending';
    const STATUS_DISTRIBUTED	= 'distributed';

    static public function getOptionArray()
    {
        return array(
            self::STATUS_PENDING    => Mage::helper('orphanpool')->__('Pending'),
            self::STATUS_DISTRIBUTED   => Mage::helper('orphanpool')->__('Distributed')
        );
    }
}