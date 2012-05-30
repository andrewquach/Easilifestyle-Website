<?php

class Vits_Orphanpool_Block_Adminhtml_Orphanpool_Detail extends Mage_Adminhtml_Block_Widget_Grid
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
      
      $time = Mage::registry('orphanpool_data')->getOrphanTime();
	  $from = date('Y-m', strtotime($time)).'-01';
	  $to = date('Y-m-d', strtotime($time));
	  //$collection->getSelect()->where('orphanpool_id = ?',$id);
	  //$collection-> getSelect()->join(array('b' => $resource->getTableName('groupbv')), 'main_table.orphanpool_id = b.id', array('b.*'))->where('orphanpool_id = ?',$id);
	  $groupbv_collection = Mage::getModel('ambassador/groupbv')->getCollection();
	  $groupbv_collection->getSelect()->where('bv_parent_id  = 0');
	  $groupbv_collection->getSelect()->where('create_date >= ?',$from);
	  $groupbv_collection->getSelect()->where('create_date <= ?',$to);
	  $this->setCollection($groupbv_collection);
      return parent::_prepareCollection();
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
	 $this->addColumn('customer_firstname', array(
          'header'    => Mage::helper('orphanpool')->__('Customer First Name'),
          'align'     =>'left',
          'index'     => 'customer_firstname',
      ));	
	 
     $this->addColumn('customer_lastname', array(
          'header'    => Mage::helper('orphanpool')->__('Customer Last Name'),
          'align'     =>'left',
          'index'     => 'customer_lastname',
      ));
	  
     
      $this->addColumn('bv', array(
          'header'    => Mage::helper('orphanpool')->__('BV Amount'),
          'align'     =>'left',
          'index'     => 'bv',
      ));
	  
	  $this->addColumn('create_date', array(
          'header'    => Mage::helper('orphanpool')->__('Transaction Date'),
          'align'     =>'left',
          'index'     => 'create_date',
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