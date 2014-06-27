<?php foreach($comments as $comment): ?>
<div class="well well_comment" id="c<?php echo $comment->id; ?>">
	<div>
		<?php 
		if ($comment->comm_user_id==Yii::app()->user->id) {
		echo CHtml::ajaxLink('<button class="btn btn-danger pull-right">Удалить</button>',
              $this->createUrl("comment/delete"),
              array('type' => 'POST',
                       //'update' => 'td#fieldsList',
                        //'beforeSend'=>'function(a){ jQuery("img#ajax-loader").toggle(); }',
                        'data'=>array('id'=>$comment->id),
                        //'success' => 'function(a){ alert(a); }',
                        'error' => 'function(a){ alert("Ошибка обработки запроса"); }',
                       ),
                       array('confirm'=>'Удалить поле ?','type' => 'submit','submit'=>array('comment/delete', 'id'=>$comment->id))
                           );
		}
	 ?>
	</div>
	<div class="author">
		<?php  echo CHtml::link("<span class='label label-warning'>".
                 CHtml::encode($comment->user->login)."</span>", 
                 array('user/view', 'id'=>$comment->user->id))." <b > сказал: </b>";
		 ?>
	</div>

	<br>
	<div class="content">
		<?php echo wordwrap(nl2br(CHtml::encode($comment->value)), 90, "<br />\n", 1); ?>
	</div>
	<br>
	<div class="time">
		<b>Время комментария: </b>
		<?php //echo date('F j, Y \a\t h:i a',$comment->date);
		echo $comment->date;
		 ?>
	</div>

	

</div><!-- comment -->
<?php endforeach; ?>