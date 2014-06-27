<?php /* @var $this Controller */ ?>
<?php $this->beginContent(); ?>

<div class='row'>
    <div  class='span2'>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>true, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'Мои задачи', 'url'=>Yii::app()->createUrl('user/friends', array('id'=>Yii::app()->user->id))),
        array('label'=>'Создать задачу', 'url'=>array('user/update')),
    ),
)) ?>
    </div>
    <div  class='span10'>
	<?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>

