<?php

/**
 * This is the model class for table "{{promotions}}".
 *
 * The followings are the available columns in table '{{promotions}}':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $started
 * @property string $ended
 * @property string $created
 * @property string $updated
 * @property integer $enabled
 * @property string $thumbnail
 * @property string $image
 * @property integer $promotion_type_id
 */
class Promotion extends CActiveRecord
{
	public $from;
	public $to;
	public $status;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Promotion the static model class
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
		return '{{promotions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, short_description, description, non_html, url', 'required'),
			array('title', 'length', 'max'=>500),
			array('url', 'length', 'max'=>300),
			array('title, short_description, description, instruction, non_html, from, to, status', 'safe'),
			array('position', 'numerical', 'integerOnly'=>true),
			array('ended', 'compare', 'compareAttribute'=>'started', 'operator'=>'>=', 'allowEmpty'=>false , 'message'=>'End date must be greater than or equal to Start date.'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'More Details',
			'short_description' => 'Short Description',
			'non_html' => 'Non-HTML description (for Facebook, Twitter)',
			'instruction' => 'Instruction',
			'started' => 'Starts On',
			'ended' => 'Ends On',
			'created' => 'Created',
			'updated' => 'Updated',
			'enabled' => 'Enabled',
			'thumbnail' => 'Thumbnail',
			'current_thumbnail' => 'Current Thumbnail',
			'current image' => 'Current Image',
			'url' => 'URL',
			'from' => 'Duration From',
			'to' => 'Duration To',
			'status' => 'Status',
		);
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

		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		if (!empty($this->from)) {
			$criteria->params[':from'] = date('Y-m-d', CDateTimeParser::parse($this->from, 'dd/MM/yyyy'));
			$criteria->addCondition('t.ended >= :from');
		}
		if (!empty($this->to)) {
			$criteria->params[':to'] = date('Y-m-d', CDateTimeParser::parse($this->to.' 23:59:59', 'dd/MM/yyyy hh:mm:ss'));
			$criteria->addCondition('t.started <= :to');
		}
		if ($this->status === '1') {
			$criteria->params[':now'] = date('Y-m-d', time());
			$criteria->addCondition('t.ended > :now');
		}
		else if ($this->status === '0') {
			$criteria->params[':now'] = date('Y-m-d', time());
			$criteria->addCondition('t.ended <= :now');
		}
		$criteria->order = 'position ASC';

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}