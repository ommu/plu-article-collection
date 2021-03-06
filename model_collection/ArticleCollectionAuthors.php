<?php
/**
 * ArticleCollectionAuthors
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 20 October 2016, 10:09 WIB
 * @link https://github.com/ommu/ommu-article-collection
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_article_collection_authors".
 *
 * The followings are the available columns in table 'ommu_article_collection_authors':
 * @property string $id
 * @property string $collection_id
 * @property string $author_id
 * @property string $creation_date
 * @property string $creation_id
 *
 * The followings are the available model relations:
 * @property ArticleCollections $collection
 * @property ArticleCollectionAuthor $author
 */
class ArticleCollectionAuthors extends CActiveRecord
{
	use GridViewTrait;

	public $defaultColumns = array();
	public $author_i;
	
	// Variable Search
	public $category_search;
	public $collection_search;
	public $author_search;
	public $creation_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleCollectionAuthors the static model class
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
		return 'ommu_article_collection_authors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('collection_id, author_id', 'required'),
			array('collection_id, author_id, creation_id', 'length', 'max'=>11),
			array(' 
				author_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, collection_id, author_id, creation_date, creation_id,
				category_search, collection_search, author_search, creation_search', 'safe', 'on'=>'search'),
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
			'collection' => array(self::BELONGS_TO, 'ArticleCollections', 'collection_id'),
			'author' => array(self::BELONGS_TO, 'ArticleCollectionAuthor', 'author_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'collection_id' => Yii::t('attribute', 'Collection'),
			'author_id' => Yii::t('attribute', 'Author'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'category_search' => Yii::t('attribute', 'Category'),
			'collection_search' => Yii::t('attribute', 'Collection'),
			'author_search' => Yii::t('attribute', 'Author'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search
		$criteria->with = array(
			'collection' => array(
				'alias' => 'collection',
				'select' => 'cat_id, article_id',
			),
			'collection.article' => array(
				'alias' => 'collection_article',
				'select' => 'title',
			),
			'author' => array(
				'alias' => 'author',
				'select' => 'author_name',
			),
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.id', strtolower($this->id), true);
		if(Yii::app()->getRequest()->getParam('collection'))
			$criteria->compare('t.collection_id', Yii::app()->getRequest()->getParam('collection'));
		else
			$criteria->compare('t.collection_id', $this->collection_id);
		if(Yii::app()->getRequest()->getParam('author'))
			$criteria->compare('t.author_id', Yii::app()->getRequest()->getParam('author'));
		else
			$criteria->compare('t.author_id', $this->author_id);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		if(Yii::app()->getRequest()->getParam('creation'))
			$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation'));
		else
			$criteria->compare('t.creation_id', $this->creation_id);
		
		$criteria->compare('collection.cat_id', $this->category_search);
		$criteria->compare('collection_article.title', strtolower($this->collection_search), true);
		$criteria->compare('author.author_name', strtolower($this->author_search), true);
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);

		if(!Yii::app()->getRequest()->getParam('ArticleCollectionAuthors_sort'))
			$criteria->order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'collection_id';
			$this->defaultColumns[] = 'author_id';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			if(!Yii::app()->getRequest()->getParam('collection')) {
				$this->defaultColumns[] = array(
					'name' => 'category_search',
					'value' => '$data->collection->category->category_name',
					'filter'=> ArticleCollectionCategory::getCategory(),
					'type' => 'raw',
				);
				$this->defaultColumns[] = array(
					'name' => 'collection_search',
					'value' => '$data->collection->article->title',
				);
			}
			if(!Yii::app()->getRequest()->getParam('author')) {
				$this->defaultColumns[] = array(
					'name' => 'author_search',
					'value' => '$data->author->author_name',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				if($this->author_id == 0) {
					$author_i = trim($this->author_i);
					$author = ArticleCollectionAuthor::model()->find(array(
						'select' => 'author_id, author_name',
						'condition' => 'author_name = :name',
						'params' => array(
							':name' => $author_i,
						),
					));
					if($author != null) {
						$this->author_id = $author->author_id;
					} else {
						$data = new ArticleCollectionAuthor;
						$data->author_name = $author_i;
						if($data->save()) {
							$this->author_id = $data->author_id;
						}
					}					
				}
			}
		}
		return true;
	}

}