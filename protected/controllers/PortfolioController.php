<?php

class PortfolioController extends Controller
{
    public $layout='//layouts/portfoliomenu';
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
        
        public function actionResume()
        {
            $model = User::model()->findByPK(Yii::app()->user->id);
            if (isset($_POST['User'])){
                
                    $model->resume=$_POST['User']['resume'];
                    $model->save(true, array('resume'));
                   
            }
            
            $this->render('resume', array('model'=>$model));
            
                
            
        }
        
        public function actionAdd()
        {
            $model = new Portfolio;
            if (isset($_POST['Portfolio'])) {
                $model->user_id = Yii::app()->user->id;
                $model->attributes=$_POST['Portfolio'];
                $model->save();
            }
        $this->render('addportfolio', array('model'=>$model));
        }
        
        public function actionShow()
        {
          $model=Portfolio::model()->findAllBySql(
         'SELECT *
                   from portfolio where user_id='.Yii::app()->user->id); 
          $dataProvider= new CArrayDataProvider($model, array('pagination'=>array(
		    		'pageSize'=>5,
		 	
	    		),
    			'sort'=>array(
                    'defaultOrder'=>"date DESC",
            	),
    		));
         $this->render('show', array('dataProvider'=>$dataProvider));
        }
}