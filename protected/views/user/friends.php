<?php
/* @var $this BlogMessageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Список друзей пользователя',
);

?>
	
<h1>Список друзей пользователя</h1>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'emptyText'=>'<div class="well">Списой друзей пуст</div>',
	'template'=>"{items}{pager}",
    'pager' => array(
           'firstPageLabel'=>'Первая',
           'prevPageLabel'=>'Предыдущая',
           'nextPageLabel'=>'Следующая',
           'lastPageLabel'=>'Последняя',
           'maxButtonCount'=>'10',
           'header'=>'<span>Пагинация</span><br>',

       ), 
)); ?>