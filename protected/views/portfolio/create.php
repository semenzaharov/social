<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'portfolio',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<?php echo $form->errorSummary($model, "При создании обнаружены следующие ошибки"); ?>
    
	<div class="row">
                Введите название работы: <br/>
		<?php echo $form->textField($model,'name',array('cols'=>150,'rows'=>2)); ?>
	</div>

        <div class="row">
                Введите описание работы: <br/>
		<?php echo $form->textArea($model,'descr',array('cols'=>150,'rows'=>40)); ?>
	</div>

        <div class="row">
                Введите ссылку: <br/>
		<?php echo $form->textField($model,'link',array('cols'=>150,'rows'=>2)); ?>
	</div>
    
        <div class="row buttons">
		<?php echo CHtml::submitButton('Добавить', array('class' => 'btn btn-primary')); ?>
	</div>

    
    <?php $this->endWidget(); ?>