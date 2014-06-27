<?php
if($flashes = Yii::app()->user->getFlashes()) {
    foreach($flashes as $key => $message) {
        if($key != 'counters') {
            $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'dialogbox',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Отправлено',
        'autoOpen'=>true,
        'open' => 'js:function(event, ui) {            
            setTimeout(function() {
                 $("#dialogbox").dialog("close");
            }, 1500);
        }',
    ),

));
            printf('<span class="dialog">%s</span>', $message['content']);
            $this->endWidget('zii.widgets.jui.CJuiDialog');
        }
    }
}