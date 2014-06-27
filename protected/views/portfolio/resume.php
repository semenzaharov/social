<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<?php echo $form->errorSummary($model, "При создании обнаружены следующие ошибки"); ?>
    
	<div class="row">
                Введите текст резюме: <br/>
		<?php echo $form->textArea($model,'resume',array('cols'=>150,'rows'=>40)); ?>
		
	</div>
    
        <div class="row buttons">
		<?php echo CHtml::submitButton('Обновить', array('class' => 'btn btn-primary')); ?>
	</div>

    
    <?php $this->endWidget(); ?>

