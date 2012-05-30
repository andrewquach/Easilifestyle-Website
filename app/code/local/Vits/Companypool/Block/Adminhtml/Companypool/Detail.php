<?php

class Vits_Companypool_Block_Adminhtml_Companypool_Detail extends Mage_Adminhtml_Block_Widget_Grid
{
public function __construct()
  {
      parent::__construct();
      $this->setId('companypoolGrid');
      $this->setDefaultSort('companypool_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('groupsales/groupsales')->getCollection();
	  
	  $bvrebate = Mage::getStoreConfig('admin/usezcommon/bvrebate');
	  $collection->getSelect()->where('before_groupsales  < ?',$bvrebate);
	  $collection->getSelect()->where('groupsales  > 0');
	  
	  $time = Mage::registry('companypool_data')->getCompanyTime();
	  $to = date('Y-m-d', strtotime($time));
      $collection->getSelect()->where('groupsales_month = ?',$to);
	  //$collection->getSelect()->where('create_date <= ?',$to);
	  
	  $this->setCollection($collection);
      parent::_prepareCollection();
       $this->getCollection()
       ->addCustomerName();
        return $this;
  }

  protected function _prepareColumns()
  {
      $this->addColumn('customer_name', array(
          'header'    => Mage::helper('groupsales')->__('Customer'),
          'align'     =>'left',
          'index'     => 'customer_name',
      ));

      $this->addColumn('groupsales_month', array(
          'header'    => Mage::helper('groupsales')->__('Month'),
          'align'     =>'left',
          'index'     => 'groupsales_month',
      ));
      
      $this->addColumn('before_groupsales', array(
          'header'    => Mage::helper('groupsales')->__('Personal BV'),
          'align'     =>'left',
          'index'     => 'before_groupsales',
      ));
      
      $this->addColumn('groupsales', array(
          'header'    => Mage::helper('groupsales')->__('Group Sales'),
          'align'     =>'left',
          'index'     => 'groupsales',
      ));
	  
		
		$this->addExportType('*/*/exportCsv', Mage::helper('companypool')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('companypool')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('companypool_id');
        $this->getMassactionBlock()->setFormFieldName('companypool');

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