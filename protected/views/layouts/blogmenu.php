<?php /* @var $this Controller */ ?>
<?php $this->beginContent(); ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/profile.js"></script>
<div class='row'>
    <div  class='span2'>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>true, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'Cоздать сообщение', 'url'=>array('blogMessage/create')),
        array('label'=>'Лента', 'url'=>array('blogMessage/index')),
    ),
)) ?>
    </div>
    <div  class='span10'>
	<?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>

