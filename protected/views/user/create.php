<?php
/* @var $this UserController */
/* @var $model User */

echo CHtml::form();
echo CHtml::label('Введите логин', false);
echo "</br>";
echo Chtml::textField('login');
echo "</br>";
echo CHtml::label('Введите пароль', false);
echo "</br>";
echo Chtml::textField('password');
echo "</br>";
echo CHtml::label('Введите email', false);
echo "</br>";
echo Chtml::textField('email');
echo "</br>";
echo CHtml::label('Введите Ваше имя', false);
echo "</br>";
echo Chtml::textField('name');
echo "</br>";
echo CHtml::label('Введите фамилию', false);
echo "</br>";
echo Chtml::textField('second_name');
echo "</br>";
echo Chtml::submitButton('Регистрация');
echo CHtml::endForm();