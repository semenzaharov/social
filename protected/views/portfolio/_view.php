
<?php  
  $baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();
?>
<div class="messages_read">
        <div class='blog_message'>
        
        <b><?php echo CHtml::encode($data->getAttributeLabel('Название: ')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />
        
        <b><?php echo CHtml::encode($data->getAttributeLabel('Описание: ')); ?>:</b>
	<?php echo CHtml::encode($data->descr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Ссылка ')); ?>:</b>
	<?php echo CHtml::encode($data->link); ?>
	<br />

</div>
</div>


