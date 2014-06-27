<hr>
<div class="comment">
<?php 
	$user = User::model()->findByPk($data->comm_user_id);
	$av = ($user->avatar==null) ? "../../upload/noavatar.gif" : "../..$user->avatar";
	echo "<div class='avatar_border'>";
	echo AvatarHelper::avatar(90, 90, "$user->avatar", "avatar_comment");
	echo "</div>";
	echo CHtml::link("<div class='login_comment label label-success'>".$user->login."</div>", array('user/view', 'id'=>$user->id));
	echo "<br>";
	echo "<div class='value_comment'>".wordwrap(nl2br($data->value), 100, "<br />\n", 1)."</div>";
	echo "<br>";
	echo "<div class='date_comment'>".$data->date."</div>";
 ?>
 </div>