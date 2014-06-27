<?php
$this->breadcrumbs=array(
    'Manage Posts',
);
?>
<h1>История сообщений</h1>
 
<?php 
if (Yii::app()->controller->action->id =='historygeneral')
{
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'historyGeneral',
)); 
}
else
$this->widget('zii.widgets.CListView', array(
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

 <script>
$(document).ready(function($) {
		$(document).on('click','.blog_message', function(event) 
                { 
                    var id = $(this).attr('id');
                    //alert($(this).html());
                    location.href="?r=messages/view&id="+id;
                })
}
           
        );
</script>