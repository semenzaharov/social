<?php
/* @var $this BlogMessageController */
/* @var $model BlogMessage */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'blog-message-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля с отметкой <span class="required">*</span> должны быть непустыми.</p>

	<?php echo $form->errorSummary($model, "При создании обнаружены следующие ошибки"); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>256, 'class'=>'span4')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php 
		echo $form->textArea($model,'text',array('cols'=>60,'rows'=>10, 'class'=>"span6")); 
		?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<!-- <div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('size'=>40,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div> -->

	<?php if ($model->isNewRecord): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo CHtml::textField("tags", "" , array('size'=>60,'maxlength'=>256, 'class'=>'span4')); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>
<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->