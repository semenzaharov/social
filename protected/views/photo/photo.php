<link type="text/css" href="css/nf.lightbox.css" rel="stylesheet" />
<link type="text/css" href="css/style.css" rel="stylesheet" />
<style>
  body{
    overflow-x: hidden;
  }
</style>
<script>
    var page = 1;
    function nl2br (text) {
        return text.replace(/(\r\n|\n\r|\r|\n)/g, "<br>");
    }

    function send_ajax (flag) {

        flag = flag || false;
        $.ajax({
        url: "<?php echo Yii::app()->createUrl('photo/ajaxAllComments') ?>",
        type: "POST",
        dataType: "json",
        success: function (data) {

            if (data.all=="true") {
                $('#more_comments').css({
                    visibility: 'hidden'});
            }
            else {
              $('#more_comments').css({
                    visibility: 'visible'});
            }
            if (!flag) {
                $('#comments').append(data.data);
            }
            else {
                $('#comments').html(data.data);   
                $('.error_label').html("")
                $('.file_descr').removeClass('error_area')
            }
            $('#lightbox-image-details-caption').html(nl2br(data.name))
            $('#lightbox-container-image-data-box').show()
          
          $('#ajax_load img').css("visibility", "hidden")
          if (typeof old_h==="undefined")
            old_h = 0;
          new_h = $('#comments').height()
          if (typeof old_capt==="undefined")
            old_capt = 0;
          new_capt = $('#lightbox-image-details-caption').height()
          var ch = (new_capt-old_capt>0) ?  new_capt-old_capt : 0;
            $('#jquery-overlay').height($('#jquery-overlay').height()+new_h-old_h+ch)
            old_h = new_h
            old_capt = new_capt

        },
        error: function (obj, err) {
          //alert("Error "+err);
        },
        data: {img: $('#lightbox-image').attr("src"), page: page}
      });
    }

  function ajaxComments() {
        page = 1;
        $('#ajax_load img').css("visibility", "visible")
        $('#more_comments').css("visibility", "visible")
        send_ajax(true);
    }
</script>
<script type="text/javascript">

jQuery(document).ready(function($) {
        $(document).on('click', '#more_comments', function(event) {
            page++;
            send_ajax();   
            return false;         
        });

        $(document).on('click', '#change_avatar', function(event) {
            var src = $('#lightbox-image').attr("src").substr(5)
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('user/ajaxChangeAvatar') ?>",
                type: "POST",
                //contentType: 'application/x-www-form-urlencoded;charset=urf-8',
                dataType: "text",
                success: function (data) {
                    location.href = data                  

                },
                error: function (obj, err) {
                    //alert("Error "+err);
                },
                data: {src: src}
            });         
        });

        $(document).on('click', '#comment_photo_btn', function(event) {
            var text = $('.file_descr').first().val()
            if (text=="") {
                $('.file_descr').addClass('error_area')
                $('.error_label').html('Комментарий не может быть пустым')
                return
            }
            else {
                $('.error_label').html("")
                $('.file_descr').removeClass('error_area')

            }
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('photo/ajaxAddComment') ?>",
                type: "POST",
                //contentType: 'application/x-www-form-urlencoded;charset=urf-8',
                dataType: "json",
                success: function (data) {
                  
                    if (data.all=="true") {
                        $('#more_comments').css({
                            visibility: 'hidden'});
                    }
                    else {
                      $('#more_comments').css({
                            visibility: 'visible'});
                    }
                    $('#comments').html(data.data)
                    var new_h = $('#comments').height()
                    new_capt = $('#lightbox-image-details-caption').height()
                    $('#jquery-overlay').height($('#jquery-overlay').height()+30+new_h-old_h+new_capt-old_capt)
                    old_h = new_h
                    old_capt = new_capt
                    $('.file_descr').first().val("")
                },
                error: function (obj, err) {
                    //alert("Error "+err);
                },
                data: {user_id: "<?php echo Yii::app()->user->id; ?>", value: text, img: $('#lightbox-image').attr("src"), page: page}
            });
        });
    });

$(window).load(function(){
$(function(){
  $("ul.gallery a").lightBox({
    overlayBgColor: '#000',
    txtImage: 'Фото',
    txtOf: 'из',
    txtPrev: '&nbsp;',
    txtNext: '&nbsp;',
    keyToClose: 'asxq',
    keyToPrev: 'asxz',
    keyToNext: 'asxc'
  });

});
});



</script>


<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Photo';
$this->breadcrumbs=array(
  'Photo',
);
?>
<style type="text/css">
ul.gallery { width:400px; margin:0; padding:0; list-style-type:none; }
ul.gallery img { border:1px solid #000; margin-left:5px; }
ul.gallery li { float:left; }
</style>
</head>
<body>
<div class="container-fluid">
  
<h1> Фотографии </h1>

<div class="row">

<?php
      foreach ($photos as $value) {
     // echo  $value->image;
        
        $url='../../media/'.$value->image;
        //echo $url;
     ?>
     <?php echo'<div class="span2">' ?>
     <?php //echo CHtml::link('<button class="close delete_image">&times;</button>');?>
     <?php 
     //if ($id==Yii::app()->user->id) {
       echo CHtml::link(
          '<button class="close delete_image">&times;</button>',
           array('photo/delete','delete_photo_id'=>$value->id),
           array('confirm' => 'Вы уверены что хотите удалить фото?')
      );
      //}
      ?>
  <?php echo '<ul class="gallery">' ?>


   <?php echo '<li class="span2">' ?>
      <?php echo '<a href="'.$url.'" class="photo" >' ?>
        <?php echo '<p class="cover">  <input type="hidden" value="'.$value->id.'"/><img src="'.$url.'" alt="'.$value->name.'" title="'.$value->name.'" class="img-polaroid"></img></p>' ?>
      <?php echo '</a>' ?>
       
      <?php echo '</li>' ?>
<?php echo '</ul>' ?>
    <?php echo '</div>' ?>
 
   <?php } ?> 

<?php if (count($photos)==0): ?>
    <h4 class="empty-list">Список фотографий пуст</h4>
<?php endif; ?>

        




</div>
<p></p>
<div class="row">
  <div class="span12">
<?php 
  if ($id==Yii::app()->user->id) {
    $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Добавить фотографию',
    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'large', // null, 'large', 'small' or 'mini'
    'url'=>array('photo/create'.'?album_id='.$model->album_id),
 
));
  }
?>

</div>
</div>
</div>
</body>
</html>