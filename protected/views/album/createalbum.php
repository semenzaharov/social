<?php
/* @var $this AlbumController */
/* @var $model Album */
/* @var $form CActiveForm */
?>
 
<?php $form=$this->beginWidget('CActiveForm',array(
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
    <?php /* текстовое поле названия элемента */ ?>
    <div class="field">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div> 
 
     <?php /* поле для загрузки файла */ ?>
    <div class="field">
        <?php if($model->album_image): ?>
            
        <?php endif; ?>
        <?php echo $form->labelEx($model,'Выберите обложку'); ?>
        <?php echo $form->fileField($model,'album_image'); ?>
        <?php echo $form->error($model,'album_image'); ?>
    </div>
 
    <?php /* кнопка отправки */ ?>
    <div class="button">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    
<?php $this->endWidget(); ?>