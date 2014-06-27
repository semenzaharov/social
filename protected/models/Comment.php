<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $comm_user_id
 * @property string $value
 * @property string $date
 * @property integer $blog_message_id
 *
 * The followings are the available model relations:
 * @property User $user
 * @property BlogMessage $id0
 */
class Comment extends CActiveRecord
{
	protected function beforeSave()
	{
	    if(parent::beforeSave()) {
	        if($this->isNewRecord) {
	        	$this->date = new CDbExpression('NOW()');
	        }
	        return true;
	    }
	    else {
	        return false;
	    }
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
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
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value', 'required', 'message' => 'Поле не должно быть пустым'),
			array('value', 'length', 'max'=>256, 'message' => 'Максимальная длина 256 символов'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, comm_user_id, value, date, blog_message_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'comm_user_id'),
			'message' => array(self::BELONGS_TO, 'BlogMessage', 'blog_message_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'comm_user_id' => 'Автор',
			'value' => 'Комментарий',
			'date' => 'Дата создания',
			'blog_message_id' => 'Blog Message',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('comm_user_id',$this->comm_user_id);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('blog_message_id',$this->blog_message_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getUrl($message=null)
	{
		if($message===null)
			$message=$this->message;
		return $message->url.'#c'.$this->id;
	}
}