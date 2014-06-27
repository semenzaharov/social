<?php
class AlbumController extends Controller{
   public $layout='//layouts/albummenu';
   public function accessRules()
    {
        $user_id = -1;
        if (isset($_GET['id'])) {
            $album_id = intval($_GET['id']);
            $album = Album::model()->findByPk($album_id);
            if ($album) {
                $user_id = $album->user_id;
            }
        }
        return array(
            array('allow',  
                'actions'=>array('delete'),
                'expression'=>"Yii::app()->user->id==".$user_id,
            ),
            array('allow',  
                'actions'=>array('createalbum', 'view'),
                'users'=>array('@'),
            ),          
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
   // $id=null
    public $album;

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actionCreatealbum()
    {

        $model=new Album;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Album']))
        {
            if (Yii::app()->user->getIsGuest())
            {
                $this->redirect(array("user/login"));
            }
            $model->attributes=$_POST['Album'];
            $album_image=CUploadedFile::getInstance($model,'album_image');
            if (!$model->validate() && $album_image===null) {
                $this->render('createalbum',array(
                    'model'=>$model,

                )); 
                exit();              
            }
            
            switch ($album_image->type) {
case "image/gif": $ext = '.gif'; break;
case "image/jpeg": $ext = '.jpg'; break;
case "image/png": $ext = '.png'; break;
}
$file=uniqid().$ext;

 
$model->album_image=$file;
           $album_image->saveAs(Yii::getPathOfAlias('webroot.cover').DIRECTORY_SEPARATOR.$file);
            if($model->save())
                $this->redirect(array("album/view"));
        }
          if (isset($_GET['id'])) {
            $user_id = intval($_GET['id']);
        } 
        
 $this->render('createalbum',array(
            'model'=>$model,

        ));
    }


    public function actionView($id = false){
       if ($id==false) {
        $id = Yii::app()->user->id;
       }
            $model=new Album;
       //$model->user_id=$_GET['user_id'];
        
        if(isset($_POST['Album'])){
            $model->attributes=$_POST['Album'];
            if($model->save()){
               
                $this->refresh();
            }
        }
 $album = Album::model()->findAllByAttributes(array('user_id'=>$id
    //(array('user_id'=>Yii::app()->user->id

//))
 ));
    //print_r($album);
    // echo "string";
    // exit();
        
        $this->render('view',array('model'=>$model,'album'=>$album, 'id' => $id));
    }

    public function actionDelete($id="") {

        $model = $this->loadModel($id);
        if ($model->user_id!=Yii::app()->user->id) {
            throw new CHttpException(404,'Вам недоступно это действие!');
        }
        $model->delete();

        $url = $this->createUrl("album/view");
        $this->redirect($url);
    }

    public function loadModel($id)
    {
        $model=Album::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Запрашиваемая страница недоступна!');
        return $model;
    }
   
}
?>