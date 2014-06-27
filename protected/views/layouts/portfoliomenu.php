<?php /* @var $this Controller */ ?>
<?php $this->beginContent(); ?>

<div class='row'>
    <div  class='span2'>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>true, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'Добавить', 'url'=>array('portfolio/add')),
        array('label'=>'Мое резюме', 'url'=>array('portfolio/resume')),
    ),
)) ?>
    </div>
    <div  class='span10'>
	<?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>