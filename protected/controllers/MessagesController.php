<?php

class MessagesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/messagesmenu';

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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'create', 'inbox', 'sent', 'history', 'quick', 'ajax', 'ajax2', 'historyGeneral', 'createrisunok', 'chat', 'getinfo'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Отображение отдельного сообщения
	 * 
	 */
	public function actionView($id)
	{
            $model = $this->loadModel($id);
            if (($model->status == 0)&&($model->user_id==Yii::app()->user->id)) {
                $model->status=1;
                $model->save(false, array('status'));
            }
            $send_model=new SentMessageForm;
            $this->render('view',array(
			'model'=>$model,
                        'qForm'=>$send_model,
		));
	}

	/**
	 * Создание сообщения
	 * 
	 */
	public function actionCreate()
	{
                $theme=Yii::app()->request->getQuery('theme');
                $value=Yii::app()->request->getQuery('value');
                
		if((isset($theme))&&(isset($value)))
		{
                        $model=new SentMessageForm;
                        $model->theme=$theme;
                        $model->value=$value;
                        if ($model->validate()){
                            $mes_model = new Messages;
                            $mes_model->theme=$theme;
                            $mes_model->value=$value;
                            if (Yii::app()->request->getQuery('mes_id')!=null) {
                            $mes_model->user_id=Yii::app()->request->getQuery('mes_id');
                            }
                            else if (Yii::app()->request->getQuery('id')!=null) {
                            $mes_model->user_id=Yii::app()->request->getQuery('id');
                            }
                            
                        if($mes_model->save())
                        {
                          echo json_encode(array('check'=>'1')) ;
                        } 
                        
                        }
		}
		
        }
        
        /*
         * Просмотр входящих сообщений
         */
        
        public function actionInbox()
        {
          $model=Messages::model()->findAllBySql(
                  'SELECT messages.id, messages.sender_user_id, messages.user_id,
                      messages.date, messages.theme, messages.value, messages.status,
                      user.name
                   from messages join user on (messages.sender_user_id=user.id) where user_id='.Yii::app()->user->id);
            
          $dataProvider= new CArrayDataProvider($model, array('pagination'=>array(
		    		'pageSize'=>5,
		 	
	    		),
    			'sort'=>array(
                    'defaultOrder'=>"date DESC",
            	),
    		));
            $this->render('inbox',array(
			'dataProvider'=>$dataProvider,	
				
		));
        }
        
        /*
         * Просмотр исходящих сообщений
         */
        
        
        public function actionSent()
        {
         $model=Messages::model()->findAllBySql(
                  'SELECT messages.id, messages.sender_user_id, messages.user_id,
                      messages.date, messages.theme, messages.value, messages.status,
                      user.name
                   from messages join user on (messages.user_id=user.id) where sender_user_id='.Yii::app()->user->id);
            
          $dataProvider= new CArrayDataProvider($model, array('pagination'=>array(
		    		'pageSize'=>5,
		 	
	    		),
    			'sort'=>array(
                    'defaultOrder'=>"date DESC",
            	),
    		));
            $this->render('sentbox',array(
			'dataProvider'=>$dataProvider,	
				
		));
        }
        
        /*
         * Вывод всех имён для истории
         */
        
        public function actionHistoryGeneral()
        {
          $user=Yii::app()->user->id;  
          $model=User::model()->findAllBySql('SELECT distinct id, name, second_name from user 
              where user.id in (select distinct user_id from messages where sender_user_id='.$user.')
              or user.id in (select distinct sender_user_id from messages where user_id='.$user.')');
          $dataProvider= new CArrayDataProvider($model, array('pagination'=>array(
		    		'pageSize'=>5,
		 	
	    		),
    			'sort'=>array(
                    'defaultOrder'=>"date DESC",
            	),
    		));
              
          
             $this->render('history',array(
			'dataProvider'=>$dataProvider,	
				
		));
        }
        
        /*
         * Вывод сообщений с определённым пользователем
         */
        
        
        public function actionHistory()
        {
            $id=Yii::app()->user->id;
            $user_id=Yii::app()->request->getQuery('id');
            $model=Messages::model()->findAllBySql(
                  'SELECT messages.id, messages.sender_user_id, messages.user_id,
                      messages.date, messages.theme, messages.value, messages.status,
                      user.name
                   from messages join user on (messages.user_id=user.id) 
                   where messages.sender_user_id='.$id.' and messages.user_id='.$user_id.' 
                       or (messages.sender_user_id='.$user_id.' and messages.user_id='.$id.')');
          $dataProvider= new CArrayDataProvider($model, array('pagination'=>array(
		    		'pageSize'=>5,
		 	
	    		),
    			'sort'=>array(
                    'defaultOrder'=>"date DESC",
            	),
    		));
            $this->render('history',array(
			'dataProvider'=>$dataProvider,	
				
		));
        }
        
        /*
         * Обработка AJAX
         */
              
        public function actionAjax2($mes_id)
        {
            if ($mes_id!=null) {     
                $model = Messages::model()->findBySql('select * from messages
                     where user_id='.Yii::app()->user->id.' order by date desc limit 1');
                if (isset($model)) {
                if ($mes_id == $model->id) {
                    echo json_encode(array('check'=>'0'));
                }
                else {
                    $user_info = User::model()->findByPk($model->sender_user_id);
                    echo CJSON::encode(array('theme' => $model->theme, 'value' => $model->value, 'sender_user_id'=>$model->sender_user_id, 'id'=>$model->id, 'check'=>'2', 'myid'=>$model->id, 
                        'user_info'=>$user_info, 'date'=>$model->date)); 
                }
                }
                else
                    echo json_encode(array('check'=>'3'));
            }
            else {
                $model=Messages::model()->findBySql('select id from messages
                     where user_id='.Yii::app()->user->id.' order by date desc limit 1');
                if (!isset($model)) {
                    
                }
                else {
                echo json_encode(array('check'=>'1', 'id'=>$model->id));
                }
            } 
        }


        /*
        * Работа с чатом
        */

        public function actionChat($sender_id, $page)
        {
        	$criteria=new CDbCriteria();
        	$criteria->condition="(sender_user_id = :sender_id and user_id= :user_id) or 
        	(sender_user_id = :user_id and user_id= :sender_id)";
        	$criteria->params[":sender_id"] = $sender_id;
        	$criteria->params[":user_id"] = Yii::app()->user->id;
        	$criteria->limit = 10;
        	$criteria->order = 'date desc';
        	$messages_list=Messages::model()->findAll($criteria);
			$user_info=User::model()->findByPk($sender_id);
			echo CJSON::encode(array('messages_list'=>$messages_list, 'user_info'=>$user_info));
        }

        public function actionGetinfo()
        {
        	$user_info=User::model()->findByPk(Yii::app()->user->id);
        	echo CJSON::encode($user_info);
        }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Messages']))
		{
			$model->attributes=$_POST['Messages'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Messages');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Messages('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Messages']))
			$model->attributes=$_GET['Messages'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        
     
        

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Messages the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Messages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        public function loadUser($id)
        {
                $model=Messages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
        }

	/**
	 * Performs the AJAX validation.
	 * @param Messages $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
