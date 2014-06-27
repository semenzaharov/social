<?php
class PhotoController extends Controller{
   public $layout='//layouts/photomenu';
   // $id=null

   public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

   public function accessRules()
    {
        $user_id = -1;
        if (isset($_GET['delete_photo_id'])) {
            $photo_id = intval($_GET['delete_photo_id']);
            $photo = Photo::model()->findByPk($photo_id);
            if ($photo) {
                $user_id = $photo->album->user_id;
            }
        }
        return array(
            array('allow',  
                'actions'=>array('delete'),
                'expression'=>"Yii::app()->user->id==".$user_id,
            ),
            array('allow',  
                'actions'=>array('create', 'photo', 'complete', 'ajaxAddComment', 'ajaxAllComments'),
                'users'=>array('@'),
            ),          
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionAjaxAddComment()
    {
        if(Yii::app()->request->isAjaxRequest) {
            $value = CHtml::encode(Yii::app()->request->getPost('value'));
            $page = Yii::app()->request->getPost('page');
            $user_id = Yii::app()->request->getPost('user_id');
            $img = Yii::app()->request->getPost('img');            
            $img = substr($img, 12);
            $photo = Photo::model()->find("image=:image", array(":image"=>$img));
            Yii::app()->db->createCommand()->insert('comment_photo', array(
                'comm_user_id' => $user_id,
                'value' => $value,
                'date' => new CDbExpression('NOW()'),
                'photo_id' => $photo->id,
                ));
            $criteria=new CDbCriteria;
            $criteria->condition = "photo_id=:photo_id";
            $criteria->params = array(":photo_id" => $photo->id);
            $criteria->limit = 5*$page;
            //$criteria->offset = 5*($page-1);
            $criteria->order = "date DESC";
            $count = CommentPhoto::model()->count($criteria);
            $all = "false";
            if (intval($count/5)+1==$page) {
                $all = "true";
            }
            if ($count%5==0 && $count/5==$page) {
                $all = "true";
            }
            $comments = CommentPhoto::model()->findAll($criteria);
            $commstr = "";
            foreach ($comments as $comm) {

                $commstr .= $this->renderPartial('_comment', array("data" => $comm), true, false); 
            }
            echo json_encode(array("data" => $commstr, "all" => $all));
        }
    }

    public function actionAjaxAllComments()
    {
        if(Yii::app()->request->isAjaxRequest) {
            $img = Yii::app()->request->getPost('img');
            $page = Yii::app()->request->getPost('page');
            $img = substr($img, 12);
            $photo = Photo::model()->find("image=:image", array(":image"=>$img));
            $criteria=new CDbCriteria;
            $criteria->condition = "photo_id=:photo_id";
            $criteria->params = array(":photo_id" => $photo->id);
            $criteria->limit = 5;
            $criteria->offset = 5*($page-1);
            $criteria->order = "date DESC";
            $comments = CommentPhoto::model()->findAll($criteria);
            $count = CommentPhoto::model()->count($criteria);
            $all = "false";
            if (intval($count/5)+1==$page) {
                $all = "true";
            }
            if ($count%5==0 && $count/5==$page) {
                $all = "true";
            }
            $commstr = "";
            foreach ($comments as $comm) {

                $commstr .= $this->renderPartial('_comment', array("data" => $comm), true, false); 
            }
            echo json_encode(array("data" => $commstr, "name" => $photo->name, "all" => $all));
        }   
    }

    public function actionCreate(){
       $model=new Photo;
       $album_id = intval($_GET['album_id']);
       $album = Album::model()->findByPk($album_id);
       if (!$album || $album->user_id!=Yii::app()->user->id) {
            throw new CHttpException(404,'Запрашиваемая страница недоступна!');
       }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Photo'])) {
            if (Yii::app()->user->getIsGuest())
            {
                $this->redirect(array("user/login"));
            }
            //$model->attributes=$_POST['Photo'];
            $images = CUploadedFile::getInstancesByName('images');
            if (isset($images) && count($images) > 0) {
                reset($_POST['Photo']['names']);
                foreach ($images as $image => $pic) {
                    switch ($pic->type) {
                        case "image/gif": $ext = '.gif'; break;
                        case "image/jpeg": $ext = '.jpg'; break;
                        case "image/png": $ext = '.png'; break;
                    }
                    $img_name = uniqid().$ext;
                    $path = Yii::getPathOfAlias('webroot.media').'/'.$img_name;
                    if ($pic->saveAs(Yii::getPathOfAlias('webroot.media').'/'.$img_name)) {
                        // add it to the main model now

                        $image = Yii::app()->image->load($path);
                        $image->resize(1280, 800);
                        $image->save();
                        $img_add = new Photo;
                        $img_add->image = $img_name; 
                        $img_add->album_id = $album_id; 
                        //$img_add->name = $_POST['Photo']['names'][$pic->name];
                        $key = key($_POST['Photo']['names']);
                        $img_add->name = CHtml::encode($_POST['Photo']['names'][$key]);
                        next($_POST['Photo']['names']);
                        $img_add->save();
                    }
                    else {
                         $this->render('create',array(
                            'model'=>$model,
                        ));
                    }
                }
            }
            $url = $this->createUrl("photo/photo", array('album_id' => $album_id));
            $this->redirect($url);
        }
        $this->render('create',array(
            'model'=>$model,
        ));  

    }
    
    public function actionPhoto(){
       
        $model=new Photo;
        if (isset($_GET['album_id'])) {
            $model->album_id=intval($_GET['album_id']);        
        }
        else {
            throw new CHttpException(404,'Запрашиваемая страница недоступна!');
        }
        
        $album = Album::model()->findByPk($model->album_id);
 
        if(isset($_POST['Photo'])){
            $model->attributes=$_POST['Photo'];

            if($model->save()){
               
                $this->refresh();
            }
        }
         $photos = Photo::model()->findAllByAttributes(array('album_id'=>$model->album_id));
 
        $this->render('photo',array('model'=>$model,'photos'=>$photos, 'id' => $album->user_id));
    }

    public function actionDelete($delete_photo_id="")
    {
        //if(Yii::app()->request->isPostRequest) {
        $model = $this->loadModel($delete_photo_id);
        $album_id = $model->album_id;
        $model->delete();

        $url = $this->createUrl("photo/photo", array('album_id' => $album_id));
        $this->redirect($url);
        // }
        // else
        //     throw new CHttpException(400,'Неверный запрос. Пожалуйста, не повторяйте этот запрос снова!');
    }

    public function loadModel($id)
    {
        $model=Photo::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Запрашиваемая страница недоступна!');
        return $model;
    }
    
}




?>