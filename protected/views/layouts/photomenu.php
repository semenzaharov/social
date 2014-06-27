<?php /* @var $this Controller */ ?>
<?php $this->beginContent(); ?>

<div class='row'>
    <div  class='span2'>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>true, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'Добавить фотографию', 'url'=>array('photo/create'.'?album_id='.Yii::app()->request->getQuery('album_id')))
    ),
)) ?>
    </div>
    <div  class='span10'>
	<?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>

