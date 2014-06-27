

<h1>Портфолио</h1>
 
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'emptyText'=>'<div class="well">Список пуст</div>',
    'template'=>"{items}{pager}",
    'pager' => array(
           'firstPageLabel'=>'Первая',
           'prevPageLabel'=>'Предыдущая',
           'nextPageLabel'=>'Следующая',
           'lastPageLabel'=>'Последняя',
           'maxButtonCount'=>'10',
          )
    
));
?> 
