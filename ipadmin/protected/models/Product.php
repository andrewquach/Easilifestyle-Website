<?php

/**
 * This is the model class for table "{{products}}".
 *
 * The followings are the available columns in table '{{products}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created
 * @property string $updated
 * @property integer $enabled
 * @property string $thumbnail
 * @property string $price
 * @property string $el_points
 * @property string $bv_points
 * @property integer $status_id
 */
class Product extends CActiveRecord
{
	public $import;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Product the static model class
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
		return '{{products}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>100),
			array('url', 'length', 'max'=>300),
			array('name, price, old_price, el_points, bv_points, category_id, non_html, url, description', 'required'),
			array('price, old_price', 'length', 'max'=>8),
			array('price, old_price, el_points, bv_points', 'numerical', 'min'=>0),
			array('position', 'numerical', 'integerOnly'=>true),
			array('price, old_price, el_points, bv_points, name, description, non_html, status_id, category_id', 'safe'),
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
			'images' => array(self::HAS_MANY, 'ProductImage', 'product_id'),
			'reviews' => array(self::HAS_MANY, 'ProductReview', 'product_id', 'order'=>'reviews.created DESC'),
			'reviewCount' => array(self::STAT, 'ProductReview', 'product_id'),
			'promotions' => array(self::HAS_MANY, 'ProductPromotion', 'product_id', 'order'=>'promotions.created'),
			'promotionCount' => array(self::STAT, 'ProductPromotion', 'product_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'status' => array(self::BELONGS_TO, 'ProductStatus', 'status_id'),
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
			'non_html' => 'Non-HTML description (for Facebook, Twitter)',
			'created' => 'Created',
			'updated' => 'Updated',
			'enabled' => 'Enabled',
			'thumbnail' => 'Thumbnail',
			'price' => 'Price',
			'old_price' => 'Old Price',
			'el_points' => 'EL Points',
			'bv_points' => 'BV Points',
			'status' => 'Status',
			'category' => 'Category',
			'url' => 'URL',
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
		$criteria->with = array('category', 'images', 'reviews', 'reviewCount', 'promotions', 'promotionCount', 'status');
		$criteria->compare('t.name', $this->name, true);
		$criteria->compare('t.description', $this->description, true);
		$criteria->compare('category_id', $this->category_id, false);
		$criteria->compare('status_id', $this->status_id, false);
		$criteria->order = 't.position ASC';

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}