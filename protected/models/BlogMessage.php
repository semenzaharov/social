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
class BlogMessage extends CActiveRecord
{
	protected function beforeSave()
	{
		if(parent::beforeSave()) {
	        if($this->isNewRecord) {
	        	$this->date = new CDbExpression('NOW()');
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
		return 'blog_message';
	}

	public function afterDelete()
	{
		Comment::model()->deleteAll('blog_message_id='.$this->id);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, text', 'required', 'message' => 'Поле не должно быть пустым'),
			array('title', 'length', 'max'=>256, 'message' => 'Максимальная длина 256 символов'),
			//array('text', 'length', 'max'=>512),
			array('tags', 'match', 'pattern'=>'/^[\w\s,]+$/',
            'message'=>'В тегах можно использовать только буквы.'),
   			// // The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, title, text, date, id', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'blog_message_id', 
				'order' => 'comments.date DESC'),
			'commentCount' => array(self::STAT, 'Comment', 'blog_message_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Автор',
			'title' => 'Заголовок',
			'text' => 'Текст',
			'tags' => 'Теги',
			'date' => 'Дата создания',
			'id' => 'Id',
		);
	}

	public function getUrl()
    {
        return Yii::app()->createUrl('blogMessage/view', array(
            'id'=>$this->id,
        ));
    }

    public function addComment($comment)
    {
    	$comment->blog_message_id = $this->id;
    	$comment->comm_user_id = Yii::app()->user->id;
    	return $comment->save();
    }

    public function getShortText()
    {
    	$text=$this->text;
    	if(strlen($text)>356) {
			$pos = strpos($text, " ", 356);
			$text = substr($text, 0, $pos);
			return $text." ...";
		}
		else {
			return $text;
		}
		//return "123";
    }

    function behaviors() {
	    return array(
	        'tags' => array(
	            'class' => 'application.extensions.yii-taggable.ETaggableBehavior',
	            // Имя таблицы для хранения тегов
	            'tagTable' => 'tag',
	            // Имя кросс-таблицы, связывающей тег с моделью.
	            // По умолчанию выставляется как Имя_таблицы_моделиTag
	            'tagBindingTable' => 'blog_message_tag',
	            // Имя внешнего ключа модели в кроcc-таблице.
	            // По умолчанию равно имя_таблицы_моделиId
	            'modelTableFk' => 'blog_message_id',
	            // Имя первичного ключа тега
	            'tagTablePk' => 'id',
	            // Имя поля названия тега
	            'tagTableName' => 'name',
	            // Имя поля счетчика тега
	            // Если устанвовлено в null (по умолчанию), то не сохраняется в базе
	            'tagTableCount' => 'count',
	            // ID тега в таблице-связке
	            'tagBindingTableTagId' => 'tag_id',
	            // ID компонента, реализующего кеширование. Если false кеширование не происходит.
	            // По умолчанию ID равен false.
	            'cacheID' => false,
	 
	 
	            // Создавать несуществующие теги автоматически.
	            // При значении false сохранение выкидывает исключение если добавляемый тег не существует.
	            'createTagsAutomatically' => true,
	 
	            // Критерий по умолчанию для выборки тегов
	            // 'scope' => array(
	            //     'condition' => ' t.user_id = :user_id ',
	            //     'params' => array( ':user_id' => Yii::app()->user->id ),
	            // ),
	 
	            // Значения, которые необходимо вставлять при записи тега
	            'insertValues' => array(
	                'user_id' => Yii::app()->user->id,
	            ),
	        )
	    );
	}
}