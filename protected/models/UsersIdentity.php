<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class UsersIdentity extends CUserIdentity
{
    private $_id;
    public function authenticate()
{
        
    $record = User::model()->findByAttributes(array('login' => $this->username));
    if ($record === null) {
        $this->errorCode = self::ERROR_USERNAME_INVALID;
    } else if ($record->password !== crypt($this->password, 'string')) {
        $this->errorCode = self::ERROR_PASSWORD_INVALID;
    } else if ($record->status == 0) {
        $this->errorCode = self::ERROR_PASSWORD_INVALID;
    } else {
        $this->_id = $record->id;
        $this->setState('id', $record->id);
        $this->setState('user_name', $record->name);
        $this->errorCode = self::ERROR_NONE;
    }
    return !$this->errorCode;
}
 
    public function getId()
    {
        return $this->_id;
    }
}
?>
