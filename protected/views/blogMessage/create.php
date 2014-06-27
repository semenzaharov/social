<?php
/* @var $this BlogMessageController */
/* @var $model BlogMessage */

$this->breadcrumbs=array(
	'Blog Messages'=>array('index'),
	'Create',
);

// $this->menu=array(
// 	array('label'=>'Общая блог-лента', 'url'=>array('index')),
// );
?>

<h1>Создание записи блога</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>		

