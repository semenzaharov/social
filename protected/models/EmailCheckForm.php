<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class EmailCheckForm extends CFormModel
{
	public $email;
	

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
                    
                     array('email', 'match', 'not' => false, 
                            'pattern' => '/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/',
                            'message' => 'Неверный формат e-mail'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Проверочный код',
		);
	}
}