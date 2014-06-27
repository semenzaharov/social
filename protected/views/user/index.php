<?php
/* @var $this BlogMessageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Список пользователей',
);

// $this->menu=array(
// 	array('label'=>'Create BlogMessage', 'url'=>array('create')),
// 	array('label'=>'Manage BlogMessage', 'url'=>array('admin')),
// );
?>
<?php
// echo Yii::getPathOfAlias('webroot').'/upload/'."13.jpg"; 
//    $image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/upload/'."13.jpg");
//    $image->resize(150, 300);
//    $image->render(); // or $image->save('images/small.jpg');

 ?>
 
<h1>Список пользователей</h1>
<hr>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <?php 
        $custom_value = (empty($field)) ? "" : $field;  
     ?>

    <div class="row">
        <?php echo $form->labelEx($search,'Поиск по логину пользователя'); ?>
        <?php echo $form->textField($search,'field', array('value' => $field, 'maxlength' => 128)); ?>
        <?php echo $form->error($search,'field'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Поиск', array('class' => 'btn btn-primary')); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<hr>
<h3>
    <?php 
        if (!empty($field)) {
            echo 'Поиск по логину "'.wordwrap(CHtml::encode($field), 70, "<br />\n", 1).'"';
        }
    ?>
</h3>
	
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'template'=>"{items}{pager}",
    'emptyText'=>'<div class="well">Список пуст</div>',
    'pager' => array(
           'firstPageLabel'=>'Первая',
           'prevPageLabel'=>'Предыдущая',
           'nextPageLabel'=>'Следующая',
           'lastPageLabel'=>'Последняя',
           'maxButtonCount'=>'10',
           'header'=>'<span>Пагинация</span><br>',

       ),
)); ?>

