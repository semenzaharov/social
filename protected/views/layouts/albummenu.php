<?php /* @var $this Controller */ ?>
<?php $this->beginContent(); ?>

<div class='row'>
    <div  class='span2'>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>true, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'Добавить альбом', 'url'=>array('album/createalbum'))
    ),
)) ?>
    </div>
    <div  class='span10'>
	<?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>

