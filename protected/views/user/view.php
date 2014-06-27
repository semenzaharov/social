<?php
/* @var $this UserController */
/* @var $model User */
$form = $this->beginWidget('CActiveForm', array(
                'id' => 'SentMessageForm',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
        
            ));
?>
<?php if (Yii::app()->user->id==$model->id): ?>
<script>


	jQuery(document).ready(function($) {
		//признак начала валидации
		validate_begin = false;
		//признак успешного завершения валидации
		validate_end = false;

		$("table").on('click', 'td', function(event) {
			var is_text = $(this).children().hasClass("text_field");
			//валидация еще не прошла, не можем переходить на другой пункт
			if (validate_begin==true && validate_end==false) {
				return false;
			}
			//обнуляем признаки после успешной валидации
			if (validate_begin==true && validate_end==true) {
				validate_begin = false;
				validate_end = false;
			}
            if (this.id) {
            	// if (this.id==="about")
            	// 	return false;
            	if (!is_text) {
            		var text = $.trim($(this).html());
	            	if (this.id=="about") {
	            		//br to nl
	            		text = text.replace(/<br\s?\/?>/g,"\r");
	            		$(this).html("<textarea name='' id='' rows='5' class='span6 text_field'>"+text+"</textarea>");
	            		
	            	}
	            	else if (this.id=="gender") {
	            		var sel_man = (text=="мужской") ? "selected" : "";
	            		var sel_woman = (text=="женский") ? "selected" : "";
	            		$(this).html("<select name='gender' id='' class='text_field'><option value='0' "+sel_man+">мужской</option><option value='1' "+sel_woman+">женский</option></select>");
	            	}
	            	else {
	            		$(this).html("<input type='text' name='' class='text_field' id='' value="+text+">");
	            	}
	            	$(this).children().focus();
           		}
            }
            	
        });

        $("table").on('blur', 'td', function () {
        	var is_text = $(this).children().hasClass("text_field");
        	td = $(this)
            if (this.id) {
            	if (is_text) {
            		var value = $(this).children().val()
            		validate_begin = true;
            		$.ajax({
	                    url: "<?php echo Yii::app()->createUrl('user/ajaxUpdate') ?>",
	                    type: "POST",
	                    dataType: "json",
	                    success: function (data) {
	                    	if (data.errors.length==0) {
	                    		validate_end = true;
	                    		var id_tmp = td.attr('id')
	                    		if (id_tmp!="gender") {
	                    			if (id_tmp=="about") {
	                    				//nl to br
		                    			td.html(data.value.replace(/\r\n|\r|\n/g,"<br />"));
		                    		}
		                    		else {
		                    			td.html(data.value)
		                    		}
		                    	}
		                    	else {
		                    		td.html((data.value==0) ? "мужской" : "женский");
		                    	}
		                    }
	                    	else {
	                    		var errs = "";
	                    		errs = data.errors;
	                    		if ($("#span_"+td.attr("id")).size()===0) {

	                    			td.children().after("<span class='alert alert-error alert-valid' id='span_"+td.attr("id")+"''>"+errs+"</span>")
	                    		}
	                    		else {
	                    			$("#span_"+td.attr("id")).html(errs)
	                    		}
	                    		td.children().first().addClass("error_text_field")
	                    		td.children().focus()
	                    	}
	                    },
	                    error: function (obj, err) {
	                        //alert("Error "+err);
	                    },
	                    data: {id: this.id, value: value}
	                });
	            	
	            }
            }
        });
	});
</script>
<?php endif; ?>
<h2><?php 
	echo $model->login; 
	$is_friends = $model->isFriends($model->id);
	$is_request_i = $model->isRequestFriends($model->id, false);
	$is_request_he = $model->isRequestFriends($model->id, true);
	if (Yii::app()->user->id!=$model->id) {
		if (!$is_friends) {
			if ($is_request_i) {
				$label = "Хочу с ним дружить";
			}
			elseif ($is_request_he) {
				$label = "Хочет со мной дружить";
			}
		}
		else {
			$label = "Мой друг";
		}
              echo '<a href="#myModal" role="button" data-toggle="modal" class="btn btn-primary pull-right">Отправить сообщение</a>';
	}
	else {
		$label = "Это я";
	}
	if (isset($label)) {
		echo "<span class='label label-success friend_label'>$label</span>";
	}
?> 
</h2>
<div id="errors" class=""></div> 

<?php 
	$is_friends = $model->isFriends($model->id);
	$is_request_i = $model->isRequestFriends($model->id, false);
	$is_request_he = $model->isRequestFriends($model->id, true);
 ?>

 <?php if(Yii::app()->user->hasFlash('friendsRequestCreated')): ?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('friendsRequestCreated'); ?>
        </div>
<?php endif; ?>

<?php echo $this->renderPartial('_profile', array('model'=>$model)); ?>
<br>
<div class="row">
	<div class="span3">
		<?php echo CHtml::link('Блог пользователя',array('blogMessage/index',
	                                 'id'=>$model->id), array('class'=>'btn btn-primary')); ?>
	</div>
	<div class="span3">
		<?php echo CHtml::link('Друзья пользователя',array('user/friends',
 	                                 'id'=>$model->id), array('class'=>'btn btn-primary')); ?>
	</div>
	<div class="span4">
		<?php 
			if ($model->id==Yii::app()->user->id) {
				if ($count!=0) {
			  		echo CHtml::link('Вам предлагают дружить',array('user/friendsRequest'), array('class'=>'btn btn-primary'));
			  	}
			}	 
			else {
				if (Yii::app()->user->id!=$model->id) {
					if (!($is_friends)) {
						if ($is_request_i) {
							echo "<span class='label label-info' style='padding:9px !important;'>Ваша заявка на дружбу с этим пользователем отправлена</span>";
							
						}
						elseif ($is_request_he) {
							echo "<span class='add_friends'>Этот пользователь желает добавить вас в друзья</span><br>";
							echo CHtml::link('Принять',array('friend/update',
				                                 'from_user_id'=>$model->id), array('class'=>'btn btn-primary margin40'));
							echo CHtml::link(CHtml::encode('Отклонить'), array('friend/delete', 'my_friend_id'=>$model->id),
							  array(
							    'submit'=>array('friend/delete', 'my_friend_id'=>$model->id),
							    'class' => 'delete btn btn-primary','confirm'=>'Вы уверены что хотите отклонить предложение?'
							  )
							);
						}
						else {
							echo CHtml::link('Предложить пользователю дружить',array('friend/create',
					                                 'id'=>$model->id), array('class'=>'btn btn-primary')); 
						}
					}
					else {
						// echo CHtml::link("Удалить друга", array('friend/delete', 'my_friend_id'=>$model->id),
						// 	  array(
						// 	    'submit'=>array('friend/delete', 'my_friend_id'=>$model->id),
						// 	    'class' => 'delete btn btn-primary','confirm'=>'Вы уверены что хотите отклонить предложение?'
						// 	  )
						// 	);
						echo CHtml::link('Удалить друга',array('friend/delete',
					                                 'my_friend_id'=>$model->id), array('class'=>'btn btn-primary','confirm'=>'Вы уверены что хотите отклонить предложение?'));
					}
				}
			}
		?>
	</div>
	<div class="span6"> <br>
		<?php 
			echo CHtml::link('Альбомы пользователя',array('album/view', "id" => $model->id), array('class'=>'btn btn-primary'));
		 ?>
	</div>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Отправить сообщение</h3>
  </div>
  <div class="modal-body">
    
            <?php echo $form->labelEx($qForm,'Тема сообщения');
            echo $form->textField($qForm,'theme', array('size'=>40,'maxlength'=>150, 'class'=>'span5'));
            echo "<br/>";  
            echo $form->error($qForm,'theme'); 
            echo $form->labelEx($qForm,'Текст сообщения'); 
            echo "<br />";
            echo $form->textArea($qForm,'value', array('rows'=>6, 'cols'=>150, 'class'=>'span5')); 
            echo "<br/>"; 
            echo $form->error($qForm,'value'); ?>
            
<?php $this->endWidget(); ?>
<div class="modal-footer">
    <button id='close-button' class="btn" data-dismiss="modal" aria-hidden="true" type="button">Закрыть</button>
    <button class="btn btn-primary" id='my-btn' type="button">Отправить</button>
  </div>
</div> 

<script >    
$('#my-btn').bind('click', function()
{
     $.getJSON('<?= Yii::app()->createUrl('messages/create');?>', 
     {theme: $('#SentMessageForm_theme').val(),
      value: $('#SentMessageForm_value').val(),
      id: '<?= Yii::app()->request->getQuery('id');?>'
    })
    .success(function ()
     {
        $('#SentMessageForm_theme').val(''); 
        $('#SentMessageForm_value').val('');
        $('#close-button').trigger('click');
     }
     )
     .error(function()
     {   
     }
     )
     
}
)
</script>


