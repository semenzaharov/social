<?php
/* @var $this BlogMessageController */
/* @var $model BlogMessage */

$this->breadcrumbs=array(
	'Сообщение блога'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Общая блог-лента', 'url'=>array('index')),
	array('label'=>'Создать запись блога', 'url'=>array('create')),
	array('label'=>'Изменить запись блога', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить запись блога', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.delete_tag', function(event) {
            $del = $(this);
            $('#confirm').modal()
        });

        $('.delete_tag_btn').on('click', function () {
            tag = $del.parent().prev().children().html();
            $.ajax({
                    url: "<?php echo Yii::app()->createUrl('blogMessage/deleteTag') ?>",
                    type: "POST",
                    dataType: "text",
                    success: function (data) {
                        //alert(data);
                        $("#all_tags").html(data)
                    },
                    error: function (obj, err) {
                        alert("Error "+err);
                    },
                    data: {tag: tag, post_id: "<?php echo $model->id ?>"}
                });
            $("#confirm").modal('hide')
        });

        $('.add_tag_btn').on('click', function () {
            tags = $("#tags_to_add").val();
            $('#tags_to_add').val("");
            $.ajax({
                    url: "<?php echo Yii::app()->createUrl('blogMessage/createTag') ?>",
                    type: "POST",
                    dataType: "text",
                    success: function (data) {
                        if (data=="") {
                            //alert(1)
                            $('#empty_tag').html("<div class='alert alert-error  alert-block'><button type='button' "+
                                "class='close' data-dismiss='alert'>&times;</button><h5>Тег не должен быть пустым</h5></div>")
                        }
                        else {
                            $("#all_tags").html(data)  
                            $('#empty_tag').html("")  
                        }
                        
                    },
                    error: function (obj, err) {
                        alert("Error "+err);
                    },
                    data: {tags: tags, post_id: "<?php echo $model->id ?>"}
                });
            $("#add_tag_modal").modal('hide')
        });

        $('.add_tag').on('click', function () {
            $('#add_tag_modal').modal()
            return false;
        });
});
</script>

<!-- <h2>Запись блога</h2> -->
<div id="empty_tag"></div>
<?php if (Yii::app()->user->hasFlash('commentSubmitted')): ?>
    <div class="alert alert-success  alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5>
            <?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
        </h5>
    </div>
<?php elseif (Yii::app()->user->hasFlash('commentDelete')): ?>
    <div class="alert alert-error  alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5>
            <?php echo Yii::app()->user->getFlash('commentDelete'); ?>
        </h5>
    </div>
<?php endif; ?>

<div class="well">
    <?php 
        $user = User::model()->findByPk($model->user_id);
     ?>
    <h3>
        <?php 
            $title = CHtml::encode($model->title); 
            $title = wordwrap($title, 90, "<br />\n", 1);
            echo $title;
        ?>
        
        <?php 
            echo CHtml::link("<span class='label label-warning pull-right blog_author'>Автор: ".
                 CHtml::encode($user->login)."</span>", array('user/view', 'id'=>$user->id));
         ?>
    </h3>
    <div class="blog_message">
        <?php echo nl2br(CHtml::encode($model->text)); ?>
    </div>
    
    <b><?php echo CHtml::encode($model->getAttributeLabel('date')); ?>:</b>

    <?php echo CHtml::encode($model->date); ?>
    <?php 
        if (Yii::app()->user->id==$user->id) {
             echo CHtml::link('Изменить запись',array('blogMessage/update',
                    'id'=>$model->id), array('class'=>'btn btn-primary', 'style'=>"margin-left:30px;"));
        }
    ?>
</div>

<br>
<div id="all_tags">
<?php 
    $tags = $model->getTags();
    if (count($tags)) {
        echo "<b class='tags_list'>Список тегов записи:</b>";
    }
    foreach($tags as $tag){
        echo CHtml::link("<span class='label label-info'>".
        CHtml::encode($tag)."</span>", array('blogMessage/index', 'tagged'=>$tag), array('class'=>"link_tag"));
        //echo "<button class='close delete_tag'>&times;</button>";
        if (Yii::app()->user->id==$user->id) {
            echo CHtml::link('<button class="close delete_tag">&times;</button>');
        }
    }
 ?>
 </div>
 <?php 
    if (Yii::app()->user->id==$user->id) {
        echo CHtml::link('Добавить тег',array('#'), 
                        array('class'=>'btn btn-primary add_tag'));
    }
  ?>
 <br>
<hr>

<div id="comments">
    	
    <h3>Оставить комментарий</h3>
 
        <?php $this->renderPartial('/comment/_form',array(
            'model'=>$comment,
        )); ?>
    <hr>
    <?php if($model->commentCount>=1): ?>
        <h3>
            <?php echo "<h4>Всего ".$model->commentCount . ' комментария(-ев, -й)</h4>'; ?>
        </h3>
 
        <?php $this->renderPartial('_comments',array(
            'post'=>$model,
            'comments'=>$model->comments,
        )); ?>
    <?php endif; ?>

</div>

<div class="modal" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Действительно хотите удалить тег?</h4>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Нет, отменить</button>
        <button class="btn btn-primary delete_tag_btn">Да</button>
    </div>
</div>

<div class="modal" id="add_tag_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 id="myModalLabel">Введите новый тег (или несколько и через запятую)</h5>
    </div>
    <div class="modal-body">
        <b>Тег(и)</b>
        <input type="text" name="" id="tags_to_add">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Нет, отменить</button>
        <button class="btn btn-primary add_tag_btn">Да</button>
    </div>
</div>
