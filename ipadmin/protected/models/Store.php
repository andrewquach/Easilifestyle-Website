<?php

/**
 * This is the model class for table "{{stores}}".
 *
 * The followings are the available columns in table '{{stores}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property string $postal_code
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $long
 * @property string $lat
 * @property string $created
 * @property string $updated
 */
class Store extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Store the static model class
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
		return '{{stores}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, address, postal_code', 'required'),
			array('name', 'length', 'max'=>100),
			array('address', 'length', 'max'=>200),
			array('postal_code', 'length', 'is'=>6),
			array('postal_code', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>50),
			array('email', 'email'),
			array('name, description', 'safe'),
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
			'name' => 'Name',
			'description' => 'Description',
			'address' => 'Address',
			'postal_code' => 'Postal Code',
			'phone' => 'Phone',
			'fax' => 'Fax',
			'email' => 'Email',
			'longitude' => 'Longitude',
			'latitude' => 'Latitude',
			'created' => 'Created',
			'updated' => 'Updated',
			'map' => 'Map',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}