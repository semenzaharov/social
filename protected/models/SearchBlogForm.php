<?php 
class SearchBlogForm extends CFormModel
{
    public $field;  

    public function attributeLabels()
    {
        return array(
            'field' => 'Поле поиска',
        );
    }  
}