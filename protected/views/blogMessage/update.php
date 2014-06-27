<?php
/* @var $this BlogMessageController */
/* @var $model BlogMessage */

$this->breadcrumbs=array(
	'Blog Messages'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Общая блог-лента', 'url'=>array('index')),
	array('label'=>'Создат ьзапись блога', 'url'=>array('create')),
	array('label'=>'Изменить запись блога', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить запись блога', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Изменение записи блога</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>		
