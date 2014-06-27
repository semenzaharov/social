<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />  
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
</head>


    
<style>
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signins {
        max-width: 400px;
        max-height: 400px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      
      .form-signin-heading{
        font-size: 30px;
        height: auto;
        margin-bottom: 15px;
        }
      
      .form-inputs{
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
        margin-left:auto;
        margin-right:auto;
            
      }
      .buttons{
          margin-top: 10px;
      }

    </style>

    
<div class="form-signins">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
    
    <h1 class='form-signin-heading'>Восстановление пароля</h1>
                Пожалуйста, введите новый пароль
                <?php echo $form->labelEx($model,'Пароль'); ?>
		<?php echo $form->passwordField($model,'password', array('class' => 'form-inputs')); ?>
		<?php echo $form->error($model,'password', array('class' => 'text-error')); ?>
                <br/>
	
		<?php echo CHtml::submitButton('Отправить', array('class' => 'btn btn-primary')); ?>
                
               
                <?php $this->widget('bootstrap.widgets.TbButton',array(
                'label' => 'Назад',
                'type' => 'primary',
                'size' => 'normal',
                'url' => Yii::app()->createUrl('user/login'))); ?>
           
                
<?php $this->endWidget(); ?>
</div><!-- form -->
