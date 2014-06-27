<?php

class FriendController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
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
			array('allow', 
				'actions'=>array('create','update', 'delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Создание запроса на дружбу
	 * 
	 */
	public function actionCreate($id)
	{
		$model=new Friend;
 		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$model->first_user_id = Yii::app()->user->id;
		$model->second_user_id = $id;

		$user = User::model()->findByPk($id);
		if ($user==null)
			throw new CHttpException(404, 'Нет такого пользователя');
		if ($model->first_user_id==$model->second_user_id)
			throw new CHttpException(404, 'Нельзя подружиться с самим собой');			
		if (User::model()->isFriends($id))
			throw new CHttpException(404, 'Вы уже друзья');			
		if (User::model()->isRequestFriends($id, true) || User::model()->isRequestFriends($id, false))
			throw new CHttpException(404, 'Заявка уже отправлена');			


		$model->friend_status = Friend::FIRST_REQUEST_TO_SECOND;
		//if ($model->validate()) {
		$model->save();
		$model = new Friend;
		$model->second_user_id = Yii::app()->user->id;
		$model->first_user_id = $id;
		$model->friend_status = Friend::SECOND_REQUEST_TO_FIRST;
		$model->save();
		Yii::app()->user->setFlash('friendsRequestCreated','Ваша заявка на дружбу отправлена.');
		$this->redirect(array('user/view', 'id'=>$id));
	}
        
    public function actionUpdate($from_user_id)
	{
		$model=Friend::model()->findByAttributes(array('first_user_id'=>$from_user_id, 'second_user_id'=>Yii::app()->user->id));
		if ($model==null)
			throw new CHttpException(404,'Ошибочный запрос');
		$model->friend_status = Friend::FRIENDS;
		$model->save();

		$model=Friend::model()->findByAttributes(array('second_user_id'=>$from_user_id, 'first_user_id'=>Yii::app()->user->id));
		$model->friend_status = Friend::FRIENDS;
		$model->save();
		$this->redirect(array('user/friends', 'id'=>Yii::app()->user->id));
		
	}

	public function actionDelete($my_friend_id)
	{
		// $this->redirect(array('user/friends', 'id'=>Yii::app()->user->id));
		// exit();
		$i_am = Yii::app()->user->id;
		$first_tmp = Friend::model()->findByAttributes(array('first_user_id'=>$my_friend_id, 'second_user_id'=>$i_am));
		if ($first_tmp)
			$first_tmp->delete();
		$sec_tmp = Friend::model()->findByAttributes(array('second_user_id'=>$my_friend_id, 'first_user_id'=>$i_am));
		if ($sec_tmp)
			$sec_tmp->delete();
		// Friend::model()->findByAttributes(array('first_user_id'=>$my_friend_id, 'second_user_id'=>$i_am))->delete();
		// Friend::model()->findByAttributes(array('second_user_id'=>$my_friend_id, 'first_user_id'=>$i_am))->delete();
		$this->redirect(array('user/friends', 'id'=>Yii::app()->user->id));
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
