<?php
$this->breadcrumbs=array(
    'Manage Posts',
);
?>
<h1>Входящие сообщения</h1>
 
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