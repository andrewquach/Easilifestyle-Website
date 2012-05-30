<?php

/**
 * This is the model class for table "{{articles}}".
 *
 * The followings are the available columns in table '{{articles}}':
 * @property integer $id
 * @property string $title
 * @property string $short_content
 * @property string $long_content
 * @property string $created
 * @property string $updated
 * @property string $thumbnail
 * @property string $published_date
 * @property integer $published
 */
class Article extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Article the static model class
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
		return '{{articles}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, short_content, non_html, url', 'required'),
			array('title', 'length', 'max'=>500),
			array('url', 'length', 'max'=>300),
			array('short_content', 'length', 'max'=>2000),
			array('position', 'numerical', 'integerOnly'=>true),
			array('title, short_content, long_content, non_html', 'safe'),
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
			'short_content' => 'Short Content',
			'long_content' => 'Long Content',
			'non_html' => 'Non-HTML content (for Facebook, Twitter)',
			'created' => 'Created',
			'updated' => 'Updated',
			'thumbnail' => 'Thumbnail',
			'published_date' => 'Published Date',
			'published' => 'Published',
			'url' => 'URL',
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
		$criteria->compare('short_content',$this->short_content,true);
		$criteria->compare('long_content',$this->long_content,true);
		$criteria->order = 'position ASC';

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}