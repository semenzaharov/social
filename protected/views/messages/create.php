<?php
/* @var $this MessagesController */
/* @var $model Messages */
/* @var $form CActiveForm */
?>




<?php 
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'SentMessageForm',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
        
            ));
    ?>
               
<a href="#myModal" role="button" data-toggle="modal">Отправить сообщение</a>
        

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Отправить сообщение</h3>
  </div>
  <div class="modal-body">
    
            <?php echo $form->labelEx($qForm,'Тема сообщения'); ?>
            <?php echo $form->textField($qForm,'theme', array('size'=>40,'maxlength'=>150));
                  echo "<br/>"  ?>
            <?php echo $form->error($qForm,'theme'); ?>
            
            <?php echo $form->labelEx($qForm,'Текст сообщения'); 
                  echo "<br />";?>
            <?php echo $form->textArea($qForm,'value', array('rows'=>6, 'cols'=>150)); 
                  echo "<br/>" ?>
            <?php echo $form->error($qForm,'value'); ?>
            
    <?php $this->endWidget(); ?>
    
   
 <div class="modal-footer">
    <button id='close-button' class="btn" data-dismiss="modal" aria-hidden="true" type="button">Закрыть</button>
    <button class="my-btn" id='my-btn' type="button">Отправить</button>
  </div>
</div> </div>
  <div class="modal-footer">
    <button id='close-button' class="btn" data-dismiss="modal" aria-hidden="true" type="button">Закрыть</button>
    <button class="my-btn" id='my-btn' type="button">Отправить</button>
  </div>
</div>

<script >    
       
$('#my-btn').bind('click', function()
{
     $.getJSON('<?= Yii::app()->createUrl('messages/create');?>', 
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
            
                