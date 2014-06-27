<?php

$baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();
?>

<style>
   a:hover{
       text-decoration: none;
       
   }
   .mystyle:hover{
       background:rgba(178,178,178,0.5);
   }
</style>

<a href='<?php echo Yii::app()->createUrl('messages/history', array('id'=>$data->id)); ?>'>
<div class="mystyle">
<b><?php echo CHtml::encode($data->getAttributeLabel('От: ')); ?></b>
	<?php echo CHtml::encode($data->name); 
        echo ' ';
        echo CHtml::encode($data->second_name);
        ?>
	<br />
</div>
</a>

