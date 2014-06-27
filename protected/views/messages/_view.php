
<?php  
  $baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();
if ($data->status==0) {
    echo '<div class="messages_unread">';
}
else {
    echo '<div class="messages_read">';
}

echo "<div class='blog_message' id='$data->id'>";
?>
        <? if ((Yii::app()->controller->action->id =='inbox')) {
            echo '<b>';
            echo CHtml::encode($data->getAttributeLabel('Отправитель:'));
            echo '</b>';
            echo '   ';
        }
        else {
            echo '<b>';
            echo CHtml::encode($data->getAttributeLabel('Получатель:'));
            echo '</b>'; 
            echo '   ';
        }
	echo CHtml::encode($data->name); 
	echo '<br />';?>
        
        <b><?php echo CHtml::encode($data->getAttributeLabel('Тема сообщения ')); ?>:</b>
	<?php echo CHtml::encode($data->theme); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Дата ')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

</div>
</div>


