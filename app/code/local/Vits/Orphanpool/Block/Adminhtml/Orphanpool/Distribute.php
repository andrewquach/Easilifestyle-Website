<?php

class Vits_Orphanpool_Block_Adminhtml_Orphanpool_Distribute extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
  }

  protected function _prepareCollection()
  {
	/*	
	  $from = date('Y-m', strtotime('-1 months')).'-01';
		$to = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT bv_parent_id  , SUM(bv) FROM '.$bvTable.'
		WHERE bv_parent_id = 0 AND create_date >= \''.$from.'\' AND create_date <= \''.$to.'\' 
		GROUP BY bv_parent_id';
		$select = $read->query($sql);
	*/
      $collection = Mage::getModel('ambassador/orphanDistribute')->getCollection();
      $time = Mage::registry('orphanpool_data')->getOrphanTime();
	  $to = date('Y-m-d', strtotime($time));
	  //$collection->getSelect()->where('orphanpool_id = ?',$id);
	  //$collection-> getSelect()->join(array('b' => $resource->getTableName('groupbv')), 'main_table.orphanpool_id = b.id', array('b.*'))->where('orphanpool_id = ?',$id);
	  $collection->getSelect()->where('date = ?',$to);
	   $this->setCollection($collection);
        parent::_prepareCollection();
       $this->getCollection()->addCustomerName();
        return $this;
	  
	  
}
protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
  protected function _prepareColumns()
  {
		/*
      $this->addColumn('orphanpool_id', array(
          'header'    => Mage::helper('orphanpool')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'orphanpool_id',
      ));

      $this->addColumn('orphan_time', array(
          'header'    => Mage::helper('orphanpool')->__('Time'),
          'align'     =>'left',
          'index'     => 'orphan_time',
      ));
		*/
	 $this->addColumn('name', array(
          'header'    => Mage::helper('ambassador')->__('Customer'),
          'align'     =>'left',
          'index'     => 'name',
      ));	
	 
	  $this->addColumn('date', array(
          'header'    => Mage::helper('ambassador')->__('Month'),
          'align'     =>'left',
          'index'     => 'date',
      ));
      
      $this->addColumn('groupsales', array(
          'header'    => Mage::helper('ambassador')->__('Group Sales'),
          'align'     =>'left',
          'index'     => 'groupsales',
      ));
      
      $this->addColumn('orphan_bv', array(
          'header'    => Mage::helper('ambassador')->__('Distributed BV'),
          'align'     =>'left',
          'index'     => 'orphan_bv',
      ));
	  $store = $this->_getStore();
       
	   $this->addColumn('bonus', array(
          'header'    => Mage::helper('ambassador')->__('Bonus Commission'),
		  'type'  => 'price',
		  'currency_code' => $store->getBaseCurrency()->getCode(),
          'align'     =>'left',
          'index'     => 'bonus',
      ));
	   /*
		$this->addColumn('status', array(
          'header'    => Mage::helper('orphanpool')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              'pending' => 'Pending',
       	      'distributed' => 'Distributed',
          ),
      ));
     */
		$this->addExportType('*/*/exportCsv', Mage::helper('orphanpool')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('orphanpool')->__('XML'));
	 
      return parent::_prepareColumns();
	  
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('orphanpool_id');
        $this->getMassactionBlock()->setFormFieldName('orphanpool');

   		$this->getMassactionBlock()->addItem('back', array(
             'label'    => Mage::helper('orphanpool')->__('Back'),
             'url'      => $this->getUrl('*/*/')
            
        ));
		
        
        return $this;
    }

  public function getRowUrl($row)
  {
      return;
  }
}