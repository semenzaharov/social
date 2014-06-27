<?php
/* @var $this BlogMessageController */
/* @var $data BlogMessage */
?>

<div class="well">
	<?php 
		$user = User::model()->findByPk($data->user_id);
	 ?>
	<h3>
		<?php 
			//echo CHtml::encode($data->title); 
			$title = CHtml::encode($data->title);
			$title = wordwrap($title, 90, "<br />\n", 1);
			if (!empty($field)) {
				$title = preg_replace("#(".$field.")#si", "<span style=\"background-color: #FFFF00\">\\1</span>", $title);
			}
			echo $title;
			echo "<span class='label label-success friend_label'>Комментариев: ".count($data->comments)."</span>";
		?>
		<?php 
			echo CHtml::link("<span class='label label-warning pull-right blog_author'>Автор: ".
	             CHtml::encode($user->login)."</span>", array('user/view', 'id'=>$user->id));
		 ?>
	</h3>
	<div class="blog_message">
		<?php 
			echo CHtml::hiddenField('', $data->id);
		 ?>
		<div class="short_blog_message">
			<?php 
				echo nl2br(CHtml::encode($data->shorttext)); 
			?>
		</div>
		<br>
		<?php 
			echo CHtml::link("<span class='label blog_read_all'>Читать полностью</span>", array('blogMessage/view', 'id'=>$data->id));
		 ?>
	</div>
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>

	<?php echo CHtml::encode($data->date); ?>

	<div class="blog_tags">
		<?php 
		 	$tags = $data->getTags();
				foreach($tags as $tag){
				  // echo Yii::app()->createUrl('blogMessage/index', array('tagged'=>$tag));
				  echo CHtml::link("<span class='label label-info'>".
	             CHtml::encode($tag)."</span>", array('blogMessage/index', 'tagged'=>$tag), array('class'=>"link_tag"));
				}
		 ?>
	</div>


</div>