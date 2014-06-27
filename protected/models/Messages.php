<?php

/**
 * This is the model class for table "messages".
 *
 * The followings are the available columns in table 'messages':
 * @property integer $id
 * @property integer $user_id
 * @property string $value
 * @property string $date
 * @property integer $sender_user_id
 * @property integer $status
 * @property string $theme
 *
 * The followings are the available model relations:
 * @property User $user
 * @property User $senderUser
 */
class Messages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Messages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public $name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, value, date, sender_user_id', 'required', 'message' => 'Это поле должно быть заполнено'),
			array('user_id, sender_user_id, status', 'numerical', 'integerOnly'=>true),
			array('theme', 'length', 'max'=>120),
                        array('value', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, value, date, sender_user_id, status, theme', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'senderUser' => array(self::BELONGS_TO, 'User', 'sender_user_id'),
		);
	}
        
        public function beforeValidate() {
            parent::beforeValidate();
            $this->sender_user_id=Yii::app()->user->id;
            $this->status=0;
            $this->date=date('Y-m-d H:i:s'); 
            return true;
        }
        
   

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Отправитель',
			'value' => 'Текст',
			'date' => 'Дата',
			'sender_user_id' => 'Получатель',
			'status' => 'Status',
			'theme' => 'Тема',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('sender_user_id',$this->sender_user_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('theme',$this->theme,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}