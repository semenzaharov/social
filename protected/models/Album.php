<?php

/**
 * This is the model class for table "blog_message".
 *
 * The followings are the available columns in table 'blog_message':
 * @property integer $user_id
 * @property string $title
 * @property string $text
 * @property string $tags
 * @property string $date
 * @property integer $id
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Comment[] $comments
 */
class Album extends CActiveRecord
{
	public $image;
	protected function beforeSave()
	{
		if(parent::beforeSave()) {
	        if($this->isNewRecord) {
	            //$this->date = new CDbExpression('NOW()');
	            $this->user_id = Yii::app()->user->id;
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
	 * @return BlogMessage the static model class
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
		return 'album';
	}

	public function beforeDelete()
	{
		$photos = Photo::model()->findAll('album_id='.$this->id);
		foreach ($photos as $photo) {
			$users =  User::model()->findAll("avatar=:avatar", array(":avatar" => "/media/".$photo->image));
	        foreach ($users as $user) {
	            $user->avatar = "";
	            $user->update();
	        }
			CommentPhoto::model()->deleteAll("photo_id=:photo_id", array(":photo_id" => $photo->id));
			$photo->deleteImage();
		}
		Photo::model()->deleteAll('album_id='.$this->id);
		return parent::beforeDelete();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, album_image', 'required','message' => 'Поле не должно быть пустым'),
			array('name', 'length', 'max'=>128, 'message' => 'Максимум 128 символов'),
			array('image', 'file', 'types'=>'jpg, gif, png',
    'allowEmpty'=>true,'on'=>'insert,update'),
			//array('text', 'length', 'max'=>512),
			// array('tags', 'match', 'pattern'=>'/^[\w\s,]+$/',
   //          'message'=>'В тегах можно использовать только буквы.'),
   //      	array('tags', 'normalizeTags'),
			// // The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, user_id, name, layout_path', 'safe', 'on'=>'search'),
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
			'photos' => array(self::HAS_MANY, 'Photo', 'album_id'),
			//'comments' => array(self::HAS_MANY, 'Comment', 'blog_message_id', 
				//'order' => 'comments.date DESC'),
			//'commentCount' => array(self::STAT, 'Comment', 'blog_message_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_id' => 'Пользователь',
			'name' => 'Имя альбома',
			'layout_path' => 'Путь к обложке',
					);
	}

	public function getUrl()
    {
        return Yii::app()->createUrl('Album/createalbum', array(
            'id'=>$this->id,
        ));
    }



   

}