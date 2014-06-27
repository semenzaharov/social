<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
'id' => 'mydialog',
        'options' => array(
            'title' => 'Входящее сообщение',
            'autoOpen' => false,
            'modal' => true,
            'resizable'=> false,
            'height' => 400,
            'width' => 500
        ),
    )); ?>
 
<script>

var id = localStorage.getItem('mes_id');
checkMessage = function()
{
    
    $.getJSON('<?= Yii::app()->createUrl('messages/ajax2');?>'+'?id='+id,
    function(data){
           if (data.check==1) {
               localStorage.setItem('mes_id', data.id);
           }
           if (data.check==2) {
               localStorage.setItem('mes_id', data.id);
               id=data.id;
               $("#dialog").html(data.value);
               $("#dialog").dialog({
	    title: data.theme});
           }
setTimeout(checkMessage,10000);
    }
)
    }
    </script>
 <?php 
    $this->endWidget('zii.widgets.jui.CJuiDialog');

    ?>
    
<?php 
        echo CHtml::button('yes', array('onclick'=> 'js:checkMessage();'));
?>

    
<div id="dialog" title="Basic dialog" style="display:none;">
<p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>