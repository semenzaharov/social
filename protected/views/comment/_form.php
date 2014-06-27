<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	
	<div class="row">
		<div class="span12">
			<div class="comment">
				<?php echo $form->labelEx($model,'value'); ?>
				<?php echo $form->textArea($model,'value',array('cols'=>60,'rows'=>5, 'class'=>'span9'));  ?>
				<br>
				<?php //echo $form->textField($model,'value',array('size'=>60,'maxlength'=>256)); ?>
				<?php echo $form->error($model,'value'); ?>
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Оставить комментарий' : 'Save', 
												array('class' => 'btn btn-primary')); ?>
			</div>
			
		</div>
	</div>
	

<?php $this->endWidget(); ?>

</div><!-- form -->