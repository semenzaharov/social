<?php

class BlogMessageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $layout='//layouts/blogmenu';
	
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
		$user_id = -1;
		if (isset($_GET['id'])) {
			$blog_id = intval($_GET['id']);
			$blog = BlogMessage::model()->findByPk($blog_id);
			if ($blog) {
				$user_id = $blog->user_id;
			}
		} 
		
		return array(
			array('allow',  
				'actions'=>array('update', 'delete', 'deleteTag', 'createTag'),
				'expression'=>"Yii::app()->user->id==".$user_id,
			),
			array('allow',  
				'actions'=>array('index', 'view', 'create', 'deleteTag', "createTag", 'complete', 
					"ajaxPagination", "ajaxLongMessage"),
				'users'=>array('@'),
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
		$message = $this->loadModel($id);
		$comment = new Comment;
		if (isset($_POST['Comment']))
		{
			if (Yii::app()->user->getIsGuest())
			{
				$this->redirect(array("user/login"));
			}
			$comment->attributes = $_POST['Comment'];
			if ($message->addComment($comment))
			{
				Yii::app()->user->setFlash('commentSubmitted','Спасибо за комментарий.');
				$this->refresh();
			}
		}
		$this->render('view',array(
			'model'=>$message,
			'comment'=>$comment
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new BlogMessage;
				
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['BlogMessage']))
		{
			$model->attributes=$_POST['BlogMessage'];
			//echo $_POST['BlogMessage']['text'];
			//exit();
			$model->setTags($_POST["tags"])->save();
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['BlogMessage']))
		{
			$model->attributes=$_POST['BlogMessage'];
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
		if(Yii::app()->request->isPostRequest) {
			$model = $this->loadModel($id);
			$model->delete();
			$url = $this->createUrl("blogMessage/index");
			$this->redirect($url);
		}
		else
			throw new CHttpException(400,'Неверный запрос. Пожалуйста, не повторяйте этот запрос снова!');
	}

	/**
	 * Lists all models.
	 * @param string $search_field поле поиска по заголовку статьи
	 * @param int $id индекс владельца блога. Если false, то выводится общая блог-лента
	 */
	public function actionIndex($id = false, $search_field = null, $tagged = "")
	{
		$search = new SearchBlogForm;
		$criteria=new CDbCriteria();
		$providerParams = null;
		
		$field = "";
		
		if (isset($_POST['SearchBlogForm']['field'])) {
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
			if ($id!==false) {
				$gets['id'] = $id;
			}
			$url = $this->createUrl("blogMessage/index", $gets);
			$this->redirect($url);
		}
		if (!empty($search_field)) {	//поиск по заголовку
			$search_field = substr($search_field, 0, 256);
			$field = $search_field;
			$field = addcslashes($field, '%_'); // escape LIKE's special characters
			$criteria->condition="title like :field";
			//$criteria->params = array(':field' => "%$field%");
			$criteria->params[":field"] = "%$field%";
			$providerParams = array('search_field'=>$field);
		}
		if ($id!==false) {		//выводим сообщения только заданного пользователя

			if (!empty($criteria->condition)) {	//уже есть одно условие поиска
				$criteria->condition .= " AND ";
			}
			$criteria->condition .= "user_id=:id";
			$criteria->params["id"] = $id;
			$user = User::model()->findByPk($id);
			//$criteria->params = array("id"=>$id);	
		}
		else {
			$user = true;	//true - общая блог-лента
		}
		$criteria->order = "date DESC";
		$per_page = Yii::app()->params["postsPerPage"];
		$criteria->limit = $per_page;
		$criteria->offset = 0;
		if (!empty($tagged)) {
			$messages = BlogMessage::model()->taggedWith($tagged)->findAll($criteria);
		}
		else {
			$messages = BlogMessage::model()->findAll($criteria); 
		}
		$dataProvider=new CArrayDataProvider($messages, array(
		    'pagination'=>array(
		        'pageSize'=>5,
		        'params'=>$providerParams
		    ),
		));
		
		//$this->criteria = $criteria;

		// $this->render('index',array(
		// 	'dataProvider'=>$dataProvider,	
		// 	'search'=>$search,	
		// 	'field'=>$field,
		// 	'tagged'=>$tagged,
		// 	"id"=>$id,
		// 	'user'=>$user,
		// ));
		$this->render('index',array(
			'messages'=>$messages,	
			'search'=>$search,	
			'field'=>$field,
			'tagged'=>$tagged,
			"id"=>$id,
			'user'=>$user,
		));
	}

	public function actionAjaxPagination()
	{
		$page = Yii::app()->request->getPost('page');
		$field = Yii::app()->request->getPost('field');
		$id = Yii::app()->request->getPost('id');
		$tagged = Yii::app()->request->getPost('tagged');
		$per_page = Yii::app()->params["postsPerPage"];
		$criteria=new CDbCriteria();
		$criteria->limit = $per_page;
		$criteria->offset = ($page-1)*$per_page;
		$criteria->order = "date DESC";
		$messages = BlogMessage::model()->findAll($criteria);
		if (!empty($field)) {	//поиск по заголовку
			$search_field = substr($field, 0, 256);
			$field = $search_field;
			$field = addcslashes($field, '%_'); // escape LIKE's special characters
			$criteria->condition="title like :field";
			//$criteria->params = array(':field' => "%$field%");
			$criteria->params[":field"] = "%$field%";
		}
		if (!empty($id)) {		//выводим сообщения только заданного пользователя
			if (!empty($criteria->condition)) {	//уже есть одно условие поиска
				$criteria->condition .= " AND ";
			}
			$criteria->condition .= "user_id=:id";
			$criteria->params["id"] = $id;
			//$criteria->params = array("id"=>$id);	
		}
		if (!empty($tagged)) {
			$tagged = CHtml::encode($tagged);
			$messages = BlogMessage::model()->taggedWith($tagged)->findAll($criteria);
		}
		else {
			$messages = BlogMessage::model()->findAll($criteria); 
		}
		foreach ($messages as $data) {
			$this->renderPartial('_view', array("data" => $data, "field" => $field), false, false);	
		}

	}

	public function actionAjaxLongMessage()
	{
		$id = Yii::app()->request->getPost('id');
		$text = Yii::app()->request->getPost('text');
		$long = intval(Yii::app()->request->getPost('_long'));
		$model = $this->loadModel($id);
		if ($long===0) {
			echo nl2br($model->text);
		}
		else {
			echo nl2br($model->shorttext);
		}
	}

	private function displayTags($post)
	{
		$tags = $post->getTags();
		$res = "";
		if (count($tags)) {
        	$res = "<b class='tags_list'>Список тегов записи:</b>";
    	}
	    foreach($tags as $tag){
	        $res .= CHtml::link("<span class='label label-info'>".
	        CHtml::encode($tag)."</span>", array('blogMessage/index', 'tagged'=>$tag), array('class'=>"link_tag"));
	        $res .= CHtml::link('<button class="close delete_tag">&times;</button>');
	    }
	    return $res;
	}

	public function actionDeleteTag()
	{
		$tag = Yii::app()->request->getPost('tag');
		$post_id = Yii::app()->request->getPost('post_id');
		$post = $this->loadModel($post_id);
		$post->removeTags($tag)->save();
		$command = Yii::app()->db->createCommand();
		$command->delete("tag", "count=0 and name=:name", array(":name" => $tag));
		echo $this->displayTags($post);
	}

	public function actionCreateTag()
	{
		$tags = Yii::app()->request->getPost('tags');
		$post_id = Yii::app()->request->getPost('post_id');
		if (!empty($tags)) {
			$post = $this->loadModel($post_id);
			$post->addTags($tags)->save();
			echo $this->displayTags($post);
		}
		else {
			echo "";
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BlogMessage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BlogMessage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Запрашиваемая страница недоступна!');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BlogMessage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='blog-message-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionComplete()
    {
    	if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
       {
            /* q is the default GET variable name that is used by
            / the autocomplete widget to pass in user input
            */
          $name = $_GET['q']; 
                    // this was set with the "max" attribute of the CAutoComplete widget
          $limit = min($_GET['limit'], 50); 
          $criteria = new CDbCriteria;
          $criteria->condition = "title LIKE :sterm";
          if (isset($_GET['f'])) {
          	$id = intval($_GET['f']);
          	if ($id!==-1) {
          		$criteria->addCondition("user_id=$id");	
          	}
          	
          }
          $criteria->params = array(":sterm"=>"%$name%");
          $criteria->limit = $limit;
          $userArray = BlogMessage::model()->findAll($criteria);
          $returnVal = '';
          foreach($userArray as $userAccount)
          {
             $returnVal .= $userAccount->getAttribute('title').'|'
                                         .$userAccount->getAttribute('id')."\n";
          }
          echo $returnVal;
       }
    }


}
