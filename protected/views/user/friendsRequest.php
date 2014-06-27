<?php
/* @var $this BlogMessageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Список заявок на дружбу',
);

?>
	
<h1>Эти пользователи хотят с вами подружиться</h1>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'viewData'=>array('fr_req'=>1),
	'itemView'=>'_view',
	'emptyText'=>'<div class="well">Никто не хочет с вами дружить</div>',
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