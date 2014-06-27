<?php
/* @var $this BlogMessageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Blog Messages',
);

$this->menu=array(
	array('label'=>'Создать запись блога', 'url'=>array('create')),

);
?>

<script>

    jQuery(document).ready(function($) {
        if ($('#hidden_id').size()==1) {
            $('li.active').removeClass("active");
        }
        $('#login-form').submit(function(){
            //alert($("#message_name").val())
            $('#message_search').val($("#message_name").val())
        });
        var page = 1
        var tmp = -1;

        $(document).on("click", ".short_blog_message", function() {
            message = $(this)
            if (message.hasClass("long_blog_message")) {
                _long = 1;
            }
            else {
                _long = 0;
            }
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('blogMessage/ajaxLongMessage') ?>",
                type: "POST",
                dataType: "text",
                success: function (data) {

                    message.toggleClass("long_blog_message");
                    // alert(data.length)
                    // alert(message.html())
                    if (data.length!=$.trim(message.html()).length){
                        //alert(data)
                        message.slideUp(500, function () {
                            message.html(data)
                        }).slideDown(1000)
                    }
                    //message.slideDown(1000)
                },
                error: function (obj, err) {
                    alert("Error "+err);
                },
                data: {id: $(this).prev().val(), _long: _long}
            });
        });

        $('html, body').scrollTop(0);
        $(window).scroll(function(event) {
            if ($(window).scrollTop() == $(document).height()-$(window).height() && $(window).scrollTop()!=tmp) {
                $('#ajax_pagination img').css("visibility", "visible")
                page++
                tmp = $(window).scrollTop()
                $.ajax({
                    url: "<?php echo Yii::app()->createUrl('blogMessage/ajaxPagination') ?>",
                    type: "POST",
                    dataType: "text",
                    success: function (data) {
                        $('#ajax_pagination img').css("visibility", "hidden")
                        $('#ajax_pagination').before(data)
                        //alert(data)
                    },
                    error: function (obj, err) {
                        alert("Error "+err);
                    },
                    data: {page: page, field: "<?php echo $field; ?>", id: "<?php echo $id; ?>", tagged: "<?php echo $tagged; ?>"}
                });
            }
        });


    });
    
</script>
	
<h1>
<?php 
    $search_form = true;
    if (Yii::app()->user->id==$id) {
        echo "Мой блог";
        echo CHtml::link("Добавить запись", array('blogMessage/create'), 
                                            array('class'=>"btn btn-primary pull-right"));
    }
    else {
        if ($user!==null && $user!==true) {
            echo "Блог ".$user->login;
            if (count($user->blogMessages)===0) {
                $search_form = false;
            }
        }
        elseif ($user===true) {
            echo "Общая блог-лента";
        }
        else {
            echo "Нет такого пользователя";
            $search_form = false;
        }
    }
    if (isset($_GET['id'])) {
        echo "<input type='hidden' id='hidden_id'/>";
    }
 ?>
</h1>
<?php if (!isset($_GET['id'])): ?>
<hr>
<h4>Облако тегов</h4>
<div id="tags_cloud">
<?php 
    function tags_pop($a, $b)
    {
        return $b["count"]-$a["count"];
    }

    $tags = BlogMessage::model()->getAllTagsWithModelsCount();
    
    usort($tags, "tags_pop");
    //print_r($tags);
    $index = 0;
    foreach($tags as $tag){
        if ($index++>=10) {
            break;
        }
        echo CHtml::link("<span class='label label-info'>".
             CHtml::encode($tag['name'])."</span>", array('blogMessage/index', 'tagged'=>$tag['name']), array('class'=>"link_tag"));        
    }
?>
</div>
<?php endif; ?>
<hr>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <?php 
        $custom_value = (empty($field)) ? "" : $field;  
     ?>
    <?php if ($search_form): ?>
    <div class="row">
        <?php echo "Поиск по заголовку статьи"; ?>
        <br>
        <?php echo $form->textField($search,'field', array('class' => 'span10','value' => $custom_value, 'maxlength'=>256, 'id' => "message_name")); ?>
        <?php echo $form->error($search,'field'); ?>
        <?php 
            if ($id===false) {
                $auto_id = -1;
            }
            else {
                $auto_id = $id;
            }
         ?>
         <?php //$this->widget('CAutoComplete',
        //   array(
        //                  //name of the html field that will be generated
        //      'name'=>'message_name', 
        //                  //replace controller/action with real ids
        //      'url'=>array('blogMessage/complete'), 
        //      'max'=>10, //specifies the max number of items to display
 
        //                  //specifies the number of chars that must be entered 
        //                  //before autocomplete initiates a lookup
        //      'minChars'=>1, 
        //      'delay'=>500, //number of milliseconds before lookup occurs
        //      'matchCase'=>false, //match case when performing a lookup?
 
        //                  //any additional html attributes that go inside of 
        //                  //the input field can be defined here
        //      'htmlOptions'=>array('size'=>'40'), 
        //      'extraParams'=>array('f'=>$auto_id), 
        //      'width'=>'212',
        //      'resultsClass'=>'blog_auto_complete ac_results',
 
        //      'methodChain'=>".result(function(event,item){\$(\"#message_search\").val(item[0]);})",
        //      ));
    ?>
    <?php echo CHtml::hiddenField('message_search'); ?>
    <?php
        if ($id!==false) {
            echo CHtml::hiddenField('blog_user_id', $id);     
        } 
        
    ?>
    </div>
<?php endif; ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Поиск', array('class' => 'btn btn-primary')); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<hr>
<h3>
    <?php 
        if (!empty($field)) {
            echo 'Поиск в заголовке по слову "'.wordwrap(CHtml::encode($field), 70, "<br />\n", 1).'"';
        }
        if (!empty($tagged)) {
            echo 'Все записи с тегом "'.$tagged.'"';   
        }

     ?>
</h3>

 <?php 
//  $this->widget('zii.widgets.CListView', array(
// 	'dataProvider'=>$dataProvider,
//     'viewData' => array( 'field' => $field ),
// 	'itemView'=>'_view',
//     'emptyText'=>'<div class="well">Список пуст</div>',
//     'template'=>"{items}{pager}",
//     'pager' => array(
//            'firstPageLabel'=>'Первая',
//            'prevPageLabel'=>'Предыдущая',
//            'nextPageLabel'=>'Следующая',
//            'lastPageLabel'=>'Последняя',
//            'maxButtonCount'=>'10',
//            'header'=>'<span>Пагинация</span><br>',

//        ), 
// ));
for ($i=0; $i < count($messages); $i++) { 
    $this->renderPartial('_view', array("data" => $messages[$i], "field" => $field,
        "id" => $id, "tagged" => $tagged), false, false);
 } 
?>
<div id="ajax_pagination">
    <img src="../../images/ajax-loader.gif" alt="">
</div>


