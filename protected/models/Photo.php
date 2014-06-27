<?php

 /**
 * @property integer $id
 * @property string $name
 * @property string $image
 *@property string $album_id
 */
class Photo extends CActiveRecord{
    public $image;
    public $file;
 
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
 
    public function tableName(){
        return 'photo';
    }
 
    public function rules(){
        return array(
            array('image','required','on'=>'insert,update', 'message'=>'Поле не должно быть пустым'),
             array('image', 'file', 'types'=>'jpg, gif, png',
                'allowEmpty'=>true,'on'=>'insert,update'),
             array('id, album_id, name, path', 'safe', 'on'=>'search'),
        );
    }
 public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'album' => array(self::BELONGS_TO, 'Album', 'album_id'),
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
            'album_id' => 'Альбом',
            'name' => 'Имя фото',
            'image' => 'Путь',
                    );
    }

    protected function beforeSave(){
        if(!parent::beforeSave())
            return false;
       // 
        if(($this->scenario=='insert' || $this->scenario=='update') &&
            ($image=CUploadedFile::getInstance($this,'image'))){
            


          // $this->deleteImage(); // старый документ удалим, потому что загружаем новый
 
            //$this->image=Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$file;
           // $this->image->saveAs('Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$file
              // ');
            

        }
        return true;
    }
 
    protected function beforeDelete(){
        if(!parent::beforeDelete())
            return false;
        CommentPhoto::model()->deleteAll("photo_id=:photo_id", array(":photo_id" => $this->id));
        $users =  User::model()->findAll("avatar=:avatar", array(":avatar" => "/media/".$this->image));
        foreach ($users as $user) {
            $user->avatar = "";
            $user->update();
        }
        $this->deleteImage(); // удалили модель? удаляем и файл

        return true;
    }
 
    public function deleteImage(){
        $imagePath=Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.
            $this->image;
        if(is_file($imagePath))
            unlink($imagePath);
    }
}

?>