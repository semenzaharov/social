<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
        <?Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/NFLightBox.js');?>
        <?Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/ui/js/jquery-ui-1.10.4.custom.js');?>
         <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nf.lightbox.css" />
       

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        

<body>
    

<div class="container" style='margin-top: 40px'>
<div class="navbar">
     <?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse', // null or 'inverse'
    'brand'=>'Социальная сеть',
    'brandUrl'=>array('user/index'),
    'collapse'=>true, // requires bootstrap-responsive.css
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Моя страница', 'url'=>array('user/view', 'id' => Yii::app()->user->id)),
                array('label'=>'Сообщения', 'url'=>array('messages/inbox')),
                array('label'=>'Альбом', 'url'=>array('album/view')),
                array('label'=>'Блог', 'url'=>array('blogMessage/index', 'id'=> Yii::app()->user->id)),
                array('label'=>'Портфолио', 'url'=>array('portfolio/show')),
                array('label'=>'Работа', 'url'=>array('task/view')),
            ),
        ),
       
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>'Выйти', 'url'=>array('user/logout')),
                
            ),
        ),
    ),
)); ?>
             </div>

	<?php echo $content; ?>

<div id="popups">
  <div class="modal-header">
  Вам пришло новое сообщение
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  </div>
    <div class="modal-body">
</div>
</div>


<div id="chat">
  <div id='chat-header' class="modal-header">
  Чат с: 
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="chat-close">&times;</button>
  </div>
    <div id="chat-body" class="modal-body">
      <div class='chat-messages'>
      </div>
    </div>
<div id='send-chat' class='chat-sender-form'>
    <form id='form_chat_sender'>
        <div class="chat-avatar">
        <img id ="chat-avatar" src=""></img>
        </div>
        <div class="chat_sender_area_form">
        <textarea id='chat_sender' class='chat-sender' rows='3'></textarea>
        </div>
      </form>    
    </div>
</div>
    
</body>
</html>

<script>
    $(document).ready(function() {
      if (localStorage.getItem('user_name')!=<?= Yii::app()->user->id;?>) {
        $.getJSON('<?= Yii::app()->createUrl('messages/getinfo');?>',
          function(data){
        localStorage.setItem('user_id', data.id);
        localStorage.setItem('user_name', data.name);
        localStorage.setItem('user_avatar', data.avatar);
        if(localStorage.getItem('chat_id')) {
            
            var chat_data = new Object();
            chat_data.sender_user_id = localStorage.getItem('chat_id');
            chat_data.page=1;
            returnChat(chat_data); 
            
        }
      })
      }
     
      

      
      checkMessage();
});

checkMessage = function()
{
    $.getJSON('<?= Yii::app()->createUrl('messages/ajax2');?>'+'?mes_id='+localStorage.getItem('mes_id'),
    function(data){
           var page=1;
           if (data.check==1) {
               localStorage.setItem('mes_id', data.id);
           }
           if (data.check==2) {
               
               localStorage.setItem('mes_id', data.myid);
               if (!localStorage.getItem('chat_id'))
               {
                $('#popups').css('display', 'block' );
                $(document).on('click','#popups', function(event) 
                { 
                    
                    localStorage.setItem('chat_id', data.sender_user_id);
                    $('#popups').css('display', 'none' );
                    $('#chat').css('display', 'block' );
                    $('#chat' ).draggable({ handle: '#chat-header' });
                    inputChat(data, page);
                    $('#chat_sender').unbind('keypress');
                    $('#chat_sender').keypress(function (event) {
                      if (event.which == '13') {
                      event.preventDefault();
                      sendChat(data);
                      }
       
                     });
                  
                });
               
               
               if (data.value.length>100) {
               $('#popups .modal-body').text(data.value.substr(0, 100) + "...");
               }
               else {
                    $('#popups .modal-body').text(data.value);
               }
               $('#popups .close').bind('click', function() 
               {
                  $('#popups').css('display', 'none' ); 
               }
               )
             }
             else{
                  
                 if ((localStorage.getItem('chat_id')==null)||(localStorage.getItem('chat_id')==data.sender_user_id)) {
                    
                    messageChat(data);
               }
             }
           }
           if (data.check==3) {
               localStorage.setItem('mes_id', null);
           }
setTimeout(checkMessage,1000);
    }
)
    }

inputChat = function(data, page)
{
  $.getJSON('<?= Yii::app()->createUrl("messages/Chat");?>'+'?sender_id='+data.sender_user_id+'&page='+page,
                    {
    tags: "mount rainier",
    tagmode: "any",
    format: "json"
  }
                    )
.done(function(data) {
  var table = $("<table id = 'chat-table'></table>").addClass('foo');
  for(i=0; i<data.messages_list.length; i++){
    if (data.messages_list[i].sender_user_id==localStorage.getItem('user_id')) {
        var sender_name=localStorage.getItem('user_name');
        var avatar=localStorage.getItem('user_avatar');;
      }
      else {
        var sender_name=data.user_info.name;
        var avatar=data.user_info.avatar;
      }
    if (avatar==''){
      avatar='../../upload/noavatar.gif';
    }
    else {
      avatar='../../'+avatar;
    }
    $('#chat-avatar').attr('src', avatar);
    table.append( "<tr><td style='chat-avatar'><img src='"+avatar+"'></td><td style='chat-value'>"
       + sender_name + '<br/>' 
      
      +
      data.messages_list[i].value+" </td> <td>"+data.messages_list[i].date+"</td></tr>" );
  
  }
  $('.chat-messages').append(table);
  $('#chat-header').text('Чат с ' + data.user_info.name);
  $('#chat-header').append('<button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="chat-close">&times;</button>');
  $('#chat-close').bind('click', function() 
               {
                  $('#chat').css('display', 'none' ); 
                  $('#chat-table').remove();
                  localStorage.removeItem('chat_id');
                  $('#chat_sender').unbind('keypress');
               }
               )

}
)};

sendChat= function (data)
{
  
  $.getJSON('<?= Yii::app()->createUrl('messages/create');?>', 
     {
      theme: 'Сообщение из чата',
      value: $('#chat_sender').val(),
      id: data.sender_user_id
    });
    
    var sender_name=localStorage.getItem('user_name');
    var avatar=localStorage.getItem('user_avatar');
     if (avatar==''){
      avatar='../../upload/noavatar.gif';
    }
    else {
      avatar='../../'+avatar;
    }
   
    $('#chat-table').prepend( "<tr><td style='chat-avatar'><img src='"+avatar+"'></td><td style='chat-value'>"
       + sender_name + '<br/>' 
      
      +
      $('#chat_sender').val()+" </td> <td>"+data.date+"</td></tr>" );
      $('#chat_sender').val(''); 
};

messageChat = function (data)
{
    if (data.sender_user_id==localStorage.getItem('user_id')) {
        var sender_name=localStorage.getItem('user_name');
        var avatar=localStorage.getItem('user_avatar');;
      }
      else {
        var sender_name=data.user_info.name;
        var avatar=data.user_info.avatar;
      }
    if (avatar==''){
      avatar='../../upload/noavatar.gif';
    }
    else {
      avatar='../../'+avatar;
    }
    $('#chat-table').prepend( "<tr><td style='chat-avatar'><img src='"+avatar+"'></td><td style='chat-value'>"
       + sender_name + '<br/>' 
      
      +
      data.value+" </td> <td>"+data.date+"</td></tr>" );
  
}


returnChat = function(data)
{

                   
                    $('#chat').css('display', 'block' );
                    $('#chat' ).draggable({ handle: '#chat-header' });
                    inputChat(data, 1);
                    $('#chat_sender').unbind('keypress');
                    $('#chat_sender').keypress(function (event) {
                      if (event.which == '13') {
                      event.preventDefault();
                      sendChat(data);
                      }
})
}

</script>




