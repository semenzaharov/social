<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">
<link type="text/css" href="css/style.css" rel="stylesheet" />

<head>
<?php
  $this->pageTitle=Yii::app()->name . ' - Photo';
  $this->breadcrumbs=array(
  'Photo',
);
?>
</head>

<body>
  <div class="container-fluid">
<h1> Фотоальбом </h1>


<div class="row">


  <?php
      foreach ($album as $value) { 
        $url = Yii::app()->createUrl('photo/photo').'?album_id='.$value->id;
        $image_url='../../cover/'.$value->album_image;
  ?>
  <?php echo '<div class="span2">'?>
        <?php echo '<nobr>' ?>
        <?php echo '<b>'.CHtml::encode($value->name).'</b>'; ?>
        <br>
        <?php echo '<a href="'.$url.'" >' ?>
        <?php echo '<p class="cover"><img src="'.$image_url.'" alt="'.$value->name.'" title="'.$value->name.'" class="img-polaroid"> ' ?>
        <?php echo '</a>' ?>
        <p></p>
        <?php echo '<a href="'.$url.'" class="btn btn-small btn-info"> Просмотр </a>'?>

        <?php 
        if ($id==Yii::app()->user->id) {
        echo CHtml::link(
        '<button class="btn btn-small">Удалить</button>',
         array('album/delete','id'=>$value->id),
         array('confirm' => 'Вы уверены что хотите удалить альбом?')
    );
        }
      ?>
        
        
        <?php echo '</p></div>' ?> 
      
<?php
}?>
<?php if (count($album)==0): ?>
    <h4 class="empty-list">Список альбомов пуст</h4>
<?php endif; ?>
   <p></p> 

  </div>
<div class="row">
<div class="span12">
  <?php 
  if ($id==Yii::app()->user->id) {
    $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Добавить альбом',
    'type'=>'primary', // null, 'primary' 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'large', // null, 'large', 'small' or 'mini'
    'url'=>array("album/createalbum"),
 
));
}
?>
<p></p>
</div>
</div>
</div>
</body>
</html>