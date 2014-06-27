<?php /* @var $this Controller */ ?>
<?php $this->beginContent(); ?>

<div class='row'>
    <div  class='span2'>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>true, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'Входящие', 'url'=>array('messages/inbox')),
        array('label'=>'Исходящие', 'url'=>array('messages/sent')),
        array('label'=>'История', 'url'=>array('messages/historygeneral')),
    ),
)) ?>
    </div>
    <div  class='span10'>
	<?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>

