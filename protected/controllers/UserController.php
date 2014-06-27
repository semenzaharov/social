<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/usermenu';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
        
        public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF
			),
			
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		// if (isset($_GET['id'])) {
		// 	$user_id = intval($_GET['id']);
		// } 
		// else {
		// 	$user_id = -1;
		// }
		return array(
			// array('allow',  
			// 	'actions'=>array('update', 'delete'),
			// 	'expression'=>"Yii::app()->user->id==".$user_id,
			// ),
			array('allow',  
				'actions'=>array('index', 'view', 'friends', 'friendsRequest', "update", "ajaxUpdate", "delete", 'logout', 'ajaxChangeAvatar'),
				'users'=>array('@'),
			),			
			array('allow',  
				'actions'=>array('register', 'login', 'activate',
					 'recoveryConfirm', 'captcha', 'error', 'recovery', 'logout'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$criteria = $this->criteriaFriends();
		$count = User::model()->count($criteria);
		$qForm= new SentMessageForm;
	        $this->render('view',array(
			'model'=>$this->loadModel($id),
	                    'qForm'=> $qForm,
	                    'count'=>$count,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	     /*
         * Регистрация нового пользователя и отправка письма с ссылкой на активацию
         */
        
        public function actionRegister()
        {
            $model = new User;
		if(isset($_POST['User'])) {
                    
			$model->attributes=$_POST['User'];
                        $model->status=0;
			if($model->save()) {
                            $to = $model->email ;
                            $subject = 'Добро пожаловать в социальную сеть';
                            $message = 'Спасибо, что зарегестрировались в нашей социальной сети
                               теперь вам необходимо зарегестрировать свою учётную запись';
                            $headers  = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                            $headers .= 'From:  < semenzaharovwork@gmail.com>' . "\r\n";
                            mail($to,$subject,$message,$headers);
                            $subject2 = "Ваша ссылка для активации";
                            $message2 = "<html><body>Пожалуйста, активируйте свою учётную запись<br />".
                            Yii::app()->createAbsoluteUrl('user/activate', array('email' => $model->random_number)) . 
                            "</body></html>";
                            $model->attributes=array();
                            mail($to,$subject2,$message2,$headers);
                            $this->redirect(array('user/login'));
                       }
                        
                }
        $model->attributes=array();
	$this->renderPartial('register', array('model'=>$model), false, true);
        }
        
        /*
         *  Активация пользователя (принимается get запрос с случайной комбинацией)
         */
        public function actionActivate()
        {
             $activation = Yii::app()->request->getQuery('email');
             $model= User::model()->findByAttributes(array(
                'random_number' => $activation));
             if ($model === null) {
                throw new CHttpException(404, 'Not found');
             }
             else {
                $model->status = 1;
                $model->save(false);
                $this->renderPartial('activate', array(), false, true);
             }
        }
       
       /*
        * Отправка сообщения с ссылкой на форму с восстановлением пароля
        */
        
      public function actionRecovery()
      {
          $model = new EmailCheckForm;
          if (isset($_POST['EmailCheckForm'])) {
              $model->attributes=$_POST['EmailCheckForm'];
              if ($model->validate(array('email'))) {
                $email = $model->email;
                $model= User::model()->findByAttributes(array('email' => $email));
                if ($model == null) {
                  throw new CHttpException(404, 'Такого пользователя нет');
                }
                else {
                  $subject='Восстановление пароля';
                  $message='Ссылка на пароль: <br/>'.
                  Yii::app()->createAbsoluteUrl('user/recoveryconfirm', array('email' => $model->random_number));
                  $headers  = 'MIME-Version: 1.0' . "\r\n";
                  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                  $headers .= 'From:  < semenzaharovwork@gmail.com>' . "\r\n";
                  mail($email,$subject,$message,$headers);
                  $this->renderPartial('recoverycap');
                  return;
                }
              }
          }
          $this->renderPartial('recovery', array('model' => $model), false, true);
      }
      
      /*
       * Обработка нового пароля.
       */
      
      public function actionRecoveryConfirm()
      {
       $model = new User;
       $activation = Yii::app()->request->getQuery('email');
       if (isset($_POST['User'])) {
            $model->attributes=$_POST['User'];
            $pass = $model->password;
            if ($model->validate(array('password'))) {
                $model= User::model()->findByAttributes(array('random_number' => $activation));
                if ($model === null) {
                    throw new CHttpException(404, 'Not found');
                }
                else {
                    $model->password = crypt($pass, 'string');
                    $model->save(true, array('password', 'random_number'));
                    $this->renderPartial('recoveryconfirmcap');
                }
            }
       
       }
       else {
           $this->renderPartial('recoveryconfirm' , array ('model' => $model), false, true);
       }
      }
      
      
      /*
       * Функция авторизации пользователя
       */
      
    public function actionLogin()
	{
		$model=new LoginForm;
		
		// Обработка AJAX
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// Сбор данных
		if(isset($_POST['LoginForm'])) {
			$model->attributes=$_POST['LoginForm'];
			// Валидация и перенаправление
			if($model->validate() && $model->login()) {
				//$this->redirect(Yii::app()->user->returnUrl);
				$this->redirect(array("user/view", 'id'=>Yii::app()->user->id));
                        }
		}
		// Вывести форму логина
		$this->renderPartial('login',array('model'=>$model), false, true);
	}
        
        
        
    public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    
    /**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$id = Yii::app()->user->id;
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->image = CUploadedFile::getInstance($model, "image");
			if (is_object($model->image) && get_class($model->image)==='CUploadedFile') {
				switch ($model->image->type) {
					case "image/gif": $ext = '.gif'; break;
					case "image/jpeg":
					case "image/pjpeg":
					 				  $ext = '.jpg'; break;
					case "image/png": $ext = '.png'; break;
				}
				$uniq = uniqid();
				$path = Yii::getPathOfAlias('webroot').'/upload/'.$uniq.$ext;
				if (isset($model->avatar)) {
					$imagePath=Yii::getPathOfAlias('webroot').$model->avatar;
					if(is_file($imagePath))
			            unlink($imagePath);
				}
				$model->avatar = '/upload/'.$uniq.$ext;
			}

			if($model->save(true, array('name', 'second_name', 'age', 'gender', 'country',
				'city', 'about', 'login', 'image', 'avatar')))
			{
				if (is_object($model->image) && get_class($model->image)==='CUploadedFile') {
					
					$model->image->saveAs($path);
					//$image = Yii::app()->image->load($path);
					// $image->resize(128, 128);
					//$image->save();
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($search_field = null)
	{
		$search = new SearchBlogForm;
		$providerParams = null;

		$field = "";

		//поиск по логину
		if (isset($_POST['SearchBlogForm'])) {
			$field = $_POST['SearchBlogForm']['field'];
			/*
			При нажатии на поиск попадаем сюда. Формируем url с get переменными
			для поля, которое ищем, и id пользователя, которому принадлежит блог.
			Редиректим на url
			*/
			$gets = array();
			if (!empty($field)) {
				$gets = array("search_field"=>$field);
			}
			$url = $this->createUrl("user/index", $gets);
			$this->redirect($url);
		}

		//выводим всех, кроме себя
		$criteria=new CDbCriteria();
		$criteria->condition = "id<>".Yii::app()->user->id;

		if (!empty($search_field)) {
			$search_field = substr($search_field, 0, 128);
			$field = $search_field;
			$criteria->condition .= " and login like '%$field%'";
			$providerParams = array('search_field'=>$field);
		}
		$dataProvider=new CActiveDataProvider('User', array('pagination'=>array(
	    		'pageSize'=>5,
	    		'params'=>$providerParams	
			),
			'criteria'=>$criteria,
			'sort'=>array(
	            'defaultOrder'=>"name DESC",
	    	),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'search'=>$search,	
			'field'=>$field,
		));
	}

	public function actionFriends($id)
	{
		$criteria=new CDbCriteria();
		$status_friends = Friend::FRIENDS;
		$criteria->join = "join friend as f on t.id=f.first_user_id";
		$criteria->condition = "f.friend_status=$status_friends and f.second_user_id=:id";
		$criteria->params = array("id"=>$id);
		$dataProvider=new CActiveDataProvider('User', 
			array('criteria'=>$criteria,));
		$this->render('friends',array(
			'dataProvider'=>$dataProvider,
			
		));
	}

	private function criteriaFriends()
	{
		$id = Yii::app()->user->id;
		$criteria=new CDbCriteria();
		$status_request = Friend::FIRST_REQUEST_TO_SECOND;
		$criteria->join = "join friend as f on t.id=f.first_user_id";
		$criteria->condition = "f.friend_status=$status_request and f.second_user_id=:id";
		$criteria->params = array("id"=>$id);
		return $criteria;
	}

	/*
	* отображает заявки на дружбу, которые пришли текущему пользователю
	*/
	public function actionFriendsRequest()
	{
		$criteria = $this->criteriaFriends();
		$dataProvider=new CActiveDataProvider('User', 
			array('criteria'=>$criteria,));
		$this->render('friendsRequest',array(
			'dataProvider'=>$dataProvider,
			
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->renderPartial('error', $error);
		}
	}

	public function actionAjaxUpdate()
	{
		if(Yii::app()->request->isAjaxRequest) {
			$id = Yii::app()->request->getPost('id');
			$value = Yii::app()->request->getPost('value');
			$user = $this->loadModel(Yii::app()->user->id);
			$errors = array();
			if ($user->$id!==$value) {
				$user->$id = $value;
				if (!$user->validate(array($id))) {
					$errors = $user->getErrors($id);
				}
				else {
					$user->update();
				}
			}
			echo json_encode(array('errors' => $errors, 'value' => $value));
			//Yii::app()->end();
		}
	}

	public function actionAjaxChangeAvatar()
	{
		if(Yii::app()->request->isAjaxRequest) {
			$src = Yii::app()->request->getPost('src');
			$user = $this->loadModel(Yii::app()->user->id);
			$user->avatar = $src;
			$user->update();
			echo Yii::app()->createUrl('user/view', array("id" => Yii::app()->user->id));
		}
	}
}
