<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $second_name
 * @property integer $age
 * @property string $gender
 * @property string $country
 * @property string $city
 * @property string $about
 * @property string $login
 * @property string $password
 * @property integer $status
 * @property integer $random_number
 *
 * The followings are the available model relations:
 * @property Album[] $albums
 * @property BlogMessage[] $blogMessages
 * @property Comment[] $comments
 * @property Friend[] $friends
 * @property Friend[] $friends1
 * @property Messages[] $messages
 * @property Messages[] $messages1
 * @property PossibleFriend $possibleFriend
 * @property PossibleFriend[] $possibleFriends
 */
class User extends CActiveRecord
{
    public $verifyCode;
    public $image;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, name, second_name, login, password', 'required', 
                            'message' => 'Данное поле не должно быть пустым'),
                        array('email, login', 'unique', 'message' => 'Уже используется'),
			array('age', 'numerical', 'integerOnly'=>true, 'min'=>1, 'tooSmall'=>'не менее 1', 'max'=>120,
				'tooBig'=>'не более 120', 'message' => 'Только цифры!'),
			array('email', 'length', 'max'=>50, 'message'=>'максимальная длина 50 символов'),
                        array('email', 'match', 'not' => false, 
                            'pattern' => '/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/',
                            'message' => 'Неверный формат e-mail'),
                        array('name, second_name', 'match', 'not' => false, 'pattern' => '/^[A-Za-zА-Яа-яёЁs,]+$/u', 
                            'message' => 'Недопустимые символы'),
			array('name, second_name, country, city, password', 'length', 'max'=>45),
			array('gender', 'length', 'max'=>1),
                        array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
			array('login', 'length', 'max'=>128),
                        array('password', 'match', 'not' => false,
                            'pattern' => '/^.*(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/',
                            'message' => 'Неверный формат пароля (минимум 6 символов, большая и маленькая буква)'),
			array('about', 'safe'),
			array('image', 'file', 'types'=>'jpg, gif, png', 'wrongType' => 'Только jpg, gif, png', 'allowEmpty' => true, 'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, name, second_name, age, gender, country, city, about, login, password, status, random_number', 'safe', 'on'=>'search'),
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
			'albums' => array(self::HAS_MANY, 'Album', 'user_id'),
			'blogMessages' => array(self::HAS_MANY, 'BlogMessage', 'user_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
			'friends1' => array(self::HAS_MANY, 'Friend', 'first_user_id'),
			'friends2' => array(self::HAS_MANY, 'Friend', 'second_user_id'),
			'messages' => array(self::HAS_MANY, 'Messages', 'user_id'),
			'messages1' => array(self::HAS_MANY, 'Messages', 'sender_user_id'),
			'possibleFriend' => array(self::HAS_ONE, 'PossibleFriend', 'user_id'),
			'possibleFriends' => array(self::HAS_MANY, 'PossibleFriend', 'possible_user_id'),
			'friends'=>array(self::HAS_MANY,'User',array('id'=>'first_user_id'),'through'=>'friend'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'name' => 'Имя',
			'second_name' => 'Фамилия',
			'age' => 'Возраст',
			'gender' => 'Пол',
			'country' => 'Страна',
			'city' => 'Город',
			'about' => 'О себе',
			'login' => 'Login',
			'password' => 'Password',
			'status' => 'Status',
			'random_number' => 'Random Number',
			'image' => "Аватарка",
                        'resume' => "Резюме",
		);
	}
        
        public function afterValidate() {
            parent::afterValidate();
            if ($this->status==0){
            $this->random_number = sha1(mt_rand(10000, 99999).time().$this->password);
            $this->password=crypt($this->password, $this->blowfishSalt());
            }
        }
        
        /*
         * Генерация соли 
         */
        public function blowfishSalt()
        {
            return 'string';
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('second_name',$this->second_name,true);
		$criteria->compare('age',$this->age);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('about',$this->about,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('random_number',$this->random_number);
                $criteria->compare('resume', $this->resume);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getAllFriends($id)
	{
		$friends = Yii::app()->db->createCommand()
			    ->select('*')
			    ->from('user u')
			    ->join('friend f', 'u.id=f.first_user_id')
			    ->where('friend_status=1 and f.second_user_id=:id', array(':id'=>$id))
			    ->queryAll();
		return $friends;
	}

	public function isFriends($user_id)
	{
		$isFriends = Friend::model()->findByAttributes(array('first_user_id'=>Yii::app()->user->id,
			'second_user_id'=>$user_id, 'friend_status'=>Friend::FRIENDS));
		return ($isFriends===null)? false : true;
	}
	/*
	* отправлена ли заявка на дружбу?
	* * @return bool $isRequestFriends true - заявка отправлена
	*/
	public function isRequestFriends($user_id, $he_to_you)
	{
		$status = ($he_to_you) ? Friend::SECOND_REQUEST_TO_FIRST : Friend::FIRST_REQUEST_TO_SECOND; 
		$isRequestFriends = Yii::app()->db->createCommand()
						    ->select('*')
						    ->from('friend f')
						    ->where(array('and', "first_user_id=:p1", "second_user_id=:p2", "friend_status=:p3"),
						    					 array(":p1"=>Yii::app()->user->id, ":p2"=>$user_id, ":p3"=>$status))
						    ->queryRow();
		//echo $user_id." ".Yii::app()->user->id." ".$isRequestFriends;
		//exit();
		return ($isRequestFriends===false)? false : true;		
	}

}