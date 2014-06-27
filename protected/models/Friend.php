<?php

/**
 * This is the model class for table "friend".
 *
 * The followings are the available columns in table 'friend':
 * @property integer $first_user_id
 * @property integer $second_user_id
 * @property integer $friend_status
 *
 * The followings are the available model relations:
 * @property User $firstUser
 * @property User $secondUser
 */
/*
@property integer $friend_status

*/
class Friend extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Friend the static model class
	 */
	/*
	Значение поля status в таблице friend
	*/

	const FRIENDS = 1;
	//первый пользователь отправил заявку на дружбу второму
	const FIRST_REQUEST_TO_SECOND = 2;
	//наоборот
	const SECOND_REQUEST_TO_FIRST = 3;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'friend';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_user_id, second_user_id', 'required'),
			array('first_user_id, second_user_id, friend_status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('first_user_id, second_user_id, friend_status', 'safe', 'on'=>'search'),
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
			'firstUser' => array(self::BELONGS_TO, 'User', 'first_user_id'),
			'secondUser' => array(self::BELONGS_TO, 'User', 'second_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'first_user_id' => 'Первый пользователь',
			'second_user_id' => 'Второй пользователь',
			'friend_status' => 'Статус дружбы',
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

		$criteria->compare('first_user_id',$this->first_user_id);
		$criteria->compare('second_user_id',$this->second_user_id);
		$criteria->compare('friend_status',$this->friend_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}