<?php

/**
 * This is the model class for table "{{redemption}}".
 *
 * The followings are the available columns in table '{{redemption}}':
 * @property integer $id
 * @property integer $promotion_id
 * @property integer $client_id
 * @property string $created
 */
class Redemption extends CActiveRecord
{
	public $from;
	public $to;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Redemption the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{redemption}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('redemption_id, from, to', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'promotion' => array(self::BELONGS_TO, 'Promotion', 'promotion_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'promotion_id' => 'Promotion',
			'client_id' => 'Client',
			'created' => 'Entry Date',
			'from' => 'From',
			'to' => 'To',
		);
	}
	
	public function behaviors(){
		return array( 'CAdvancedArBehavior' => array(
            'class' => 'application.extensions.CAdvancedArBehavior'));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('promotion_id',$this->promotion_id);
		if (!empty($this->from)) {
			$criteria->params[':from'] = CDateTimeParser::parse($this->from, 'dd/MM/yyyy');
			$criteria->addCondition('t.created >= :from');
		}
		if (!empty($this->to)) {
			$criteria->params[':to'] = CDateTimeParser::parse($this->to.' 23:59:59', 'dd/MM/yyyy hh:mm:ss');
			$criteria->addCondition('t.created <= :to');
		}

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}