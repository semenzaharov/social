<?php
/* @var $this UserController */
/* @var $data User */
$is_friends = $data->isFriends($data->id);
$clas = ($is_friends) ? "well_friend" : "well";
?>

<div class='<?php echo $clas; ?>  well-small'>
	<?php 
		echo AvatarHelper::avatar(150, 150, "$data->avatar");
	 ?>
	<?php 
		echo CHtml::link("<span class='label label-info'>".
             CHtml::encode($data->login)."</span>", array('user/view', 'id'=>$data->id), array('class'=>"link_tag"));
	?>

	<?php
	 	if (isset($fr_req)) {
			echo CHtml::link('Принять',array('friend/update',
                                 'from_user_id'=>$data->id), array('class'=>'btn btn-primary margin40'));
			echo CHtml::link(CHtml::encode('Отклонить'), array('friend/delete', 'my_friend_id'=>$data->id),
			  array(
			    'submit'=>array('friend/delete', 'my_friend_id'=>$data->id),
			    'class' => 'delete btn btn-primary','confirm'=>'Вы уверены что хотите отклонить предложение?'
			  )
			);
		}
	 ?>


</div>