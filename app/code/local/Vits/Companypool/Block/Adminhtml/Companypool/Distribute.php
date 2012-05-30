<?php

class Vits_Companypool_Block_Adminhtml_Companypool_Distribute extends Mage_Adminhtml_Block_Widget_Grid
{
public function __construct()
  {
      parent::__construct();
      $this->setId('companypoolGrid');
      $this->setDefaultSort('companypool_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
  protected function _prepareCollection()
  {
      $collection = Mage::getModel('ambassador/companyDistribute')->getCollection();
	  
	  $time = Mage::registry('companypool_data')->getCompanyTime();
	  $to = date('Y-m-d', strtotime($time));
      $collection->getSelect()->where('date = ?',$to);
	  //$collection->getSelect()->where('create_date <= ?',$to);
	  
	  $this->setCollection($collection);
        parent::_prepareCollection();
       $this->getCollection()->addCustomerName();
        return $this;
        
		
  }

  protected function _prepareColumns()
  {
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
      
      $this->addColumn('company_bv', array(
          'header'    => Mage::helper('ambassador')->__('Distributed BV'),
          'align'     =>'left',
          'index'     => 'company_bv',
      ));
	  
	  $store = $this->_getStore();
       
	   $this->addColumn('bonus', array(
          'header'    => Mage::helper('ambassador')->__('Bonus Commission'),
		  'type'  => 'price',
		  'currency_code' => $store->getBaseCurrency()->getCode(),
          'align'     =>'left',
          'index'     => 'bonus',
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