<?php

class Vits_Affiliatecommission_Block_Adminhtml_Affiliatecommission_Detail extends Mage_Adminhtml_Block_Widget_Grid
{
public function __construct()
  {
      parent::__construct();
      $this->setId('affiliatecommissionDetailGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('ambassador/affiliatebv')->getCollection();
	  $this->setCollection($collection);
	  $this->renderr($collection);
      return parent::_prepareCollection();
  }

  protected function renderr($collection)
  {
	  $collection->getSelect()->where('customer_id  = ?',Mage::registry('affiliatecommission_data')->getCustomerId());
	  $time = Mage::registry('affiliatecommission_data')->getAffiliateTime();
	  
	  $from = date('Y-m', strtotime($time.'-1 months')).'-01';
	  $to = date('Y-m-d', strtotime($time.'-01 -1 days'));
	  
	  $collection->getSelect()->where('create_date >= ?',$from);
	  $collection->getSelect()->where('create_date <= ?',$to);
  }
  
  protected function _prepareColumns()
  {
	  $this->addColumn('id', array(
          'header'    => Mage::helper('affiliatecommission')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));	
  
      $this->addColumn('customer_firstname', array(
          'header'    => Mage::helper('affiliatecommission')->__('Customer First Name'),
          'align'     =>'left',
          'index'     => 'customer_firstname',
      ));

      $this->addColumn('customer_lastname', array(
          'header'    => Mage::helper('affiliatecommission')->__('Customer Last Name'),
          'align'     =>'left',
          'index'     => 'customer_lastname',
      ));
      
	  $this->addColumn('order_id', array(
          'header'    => Mage::helper('affiliatecommission')->__('Order ID'),
          'align'     =>'left',
          'index'     => 'order_id',
      ));
	  
	  $this->addColumn('item', array(
          'header'    => Mage::helper('affiliatecommission')->__('Item'),
          'align'     =>'left',
          'index'     => 'item',
      ));
	  
	  $this->addColumn('sku', array(
          'header'    => Mage::helper('affiliatecommission')->__('SKU'),
          'align'     =>'left',
          'index'     => 'sku',
      ));
	  
	  $this->addColumn('quantity', array(
          'header'    => Mage::helper('affiliatecommission')->__('Quantity'),
          'align'     =>'left',
          'index'     => 'quantity',
      ));
	  
	  $this->addColumn('bv', array(
          'header'    => Mage::helper('affiliatecommission')->__('BV'),
          'align'     =>'left',
          'index'     => 'bv',
      ));
	  
		
		//$this->addExportType('*/*/exportCsvDetail', Mage::helper('affiliatecommission')->__('CSV'));
		//$this->addExportType('*/*/exportXmlDetail', Mage::helper('affiliatecommission')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('affiliatecommissiondetail');

		$this->getMassactionBlock()->addItem('back', array(
             'label'    => Mage::helper('affiliatecommission')->__('Back'),
             'url'      => $this->getUrl('*/*/')
            
        ));
		
		$this->getMassactionBlock()->addItem('export', array(
                'label' => Mage::helper('catalog')->__('Export to CSV'),
                'url'   => $this->getUrl('*/*/massExport', array('_current'=>true)),
            ));
		
        return $this;
    }

  public function getRowUrl($row)
  {
      return;
  }

}