<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class SentMessageForm extends CFormModel
{
    public $theme;
    public $value;
  
    public function rules()
    {
        return array(
            array('value, theme', 'required', 'message'=>'Данное поле должно быть заполнено'),
            array('theme', 'length', 'max'=>120, 'message'=>'Макс. длина данного поля - 120 символов'),
            array('value', 'length', 'max'=>255, 'message'=>'Макс. длина данного поля - 255 символов'),
        );
    }
  
    public function attributeLabels()
    {
        return array(
            'theme'=>'Тема сообщения',
            'value'=>'Текст',
        );
    }
}
?>
