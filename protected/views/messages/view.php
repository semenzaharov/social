<?php
/* @var $this MessagesController */
/* @var $model Messages */

$this->breadcrumbs=array(
	'Messages'=>array('index'),
	$model->id,
);
?>

<h1>Сообщение #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
                'date',
		'theme',
                'value',
	),
     'htmlOptions'=>array('class'=>'table table-striped well', 'style' => 'word-wrap: break-word; table-layout: fixed; width: 100%;')
)); ?>


<div class="form">
<?php
 $form = $this->beginWidget('CActiveForm', array(
                'id' => 'SentMessageForm',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
        
            ));
 ?>

</div><!-- form -->
<?php
echo '<a href="#myModal" role="button" data-toggle="modal" class="btn btn-primary">Ответить собеседнику</a>';
?>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Отправить сообщение</h3>
  </div>
  <div class="modal-body">
    
            <?php echo $form->labelEx($qForm,'Тема сообщения');
            echo $form->textField($qForm,'theme', array('size'=>40,'maxlength'=>150, 'class'=>'span5'));
            echo "<br/>";  
            echo $form->error($qForm,'theme'); 
            echo $form->labelEx($qForm,'Текст сообщения'); 
            echo "<br />";
            echo $form->textArea($qForm,'value', array('rows'=>6, 'cols'=>150, 'class'=>'span5')); 
            echo "<br/>"; 
            echo $form->error($qForm,'value'); ?>
            
<?php $this->endWidget(); ?>
<div class="modal-footer">
    <button id='close-button' class="btn" data-dismiss="modal" aria-hidden="true" type="button">Закрыть</button>
    <button class="btn btn-primary" id='my-btn' type="button">Отправить</button>
  </div>
</div> 

<script >    
$('#my-btn').bind('click', function()
{
     $.getJSON('<?php
      if ($model->sender_user_id==Yii::app()->user->id){ 
         echo Yii::app()->createUrl('messages/create', array('mes_id'=>$model->user_id));
      }
      else {
         echo Yii::app()->createUrl('messages/create', array('mes_id'=>$model->sender_user_id));
      }
?>', 
     {theme: $('#SentMessageForm_theme').val(),
      value: $('#SentMessageForm_value').val(),
      id: '<?= Yii::app()->request->getQuery('id');?>'
    })
    .success(function ()
     {
        $('#SentMessageForm_theme').val(''); 
        $('#SentMessageForm_value').val('');
        $('#close-button').trigger('click');
     }
     )
     .error(function()
     {   
     }
     )
     
}
)
</script>
