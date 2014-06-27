<?php
/* @var $this PhotoController */
/* @var $model Photo */
/* @var $form CActiveForm */
?>

<script>
    function after_load_file(v) {
        $('.MultiFile-label').last().append("<br/>Описание фото <br/> <textarea name='Photo[names]["+v+"]' cols='10' rows='5' class='file_descr span4'></textarea>")
    }

    jQuery(document).ready(function($) {
        $(document).on('click', '.MultiFile-remove', function(event) {
            //event.preventDefault();
            //alert(1)
        });
    });
</script>
 
<?php $form=$this->beginWidget('CActiveForm',array(
    'id' => 'album-form',
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
    
   <?php 
        $this->widget('CMultiFileUpload', array(
                'name' => 'images',
                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
                'duplicate' => 'Уже есть!', // useful, i think
                'denied' => 'Неправильный формат файла', // useful, i think
                'options'=>array(
                    // 'onFileSelect'=>'function(e, v, m){ alert("onFileSelect - "+v) }',
                    // 'afterFileSelect'=>'function(e, v, m){ alert("afterFileSelect - "+v) }',
                    //'onFileAppend'=>'function(e, v, m){  }',
                    'afterFileAppend'=>"function(e, v, m){ after_load_file(v) }",
                    //'onFileRemove'=>'function(e, v, m){  }',
                     //'afterFileRemove'=>'function(e, v, m){  }',
                 ),
            ));
    ?>        
 
    <?php /* поле для загрузки файла */ ?>
    <!-- <div class="field">
        <?php if($model->image): ?>
            
        <?php endif; ?>
        <?php echo $form->labelEx($model,'image'); ?>
        <?php echo $form->fileField($model,'image'); ?>
        <?php echo $form->error($model,'image'); ?>
    </div> -->
 
    <?php /* кнопка отправки */ ?>
    <div class="button">
        <br>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'btn btn-primary')); ?>
    </div>
<?php $this->endWidget(); ?>

<div class="modal" id="add_file_descr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 id="myModalLabel">Введите новый тег (или несколько и через запятую)</h5>
    </div>
    <div class="modal-body">
        <b>Тег(и)</b>
        <input type="text" name="" id="tags_to_add">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Нет, отменить</button>
        <button class="btn btn-primary add_tag_btn">Да</button>
    </div>
</div>