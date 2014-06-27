<?php
 
class AvatarHelper
{
    public static function avatar($width, $height, $img="", $class="", $alt="")
    {
        if ($img=="") {
        	$img = "/upload/noavatar.gif";
        }

        $src = Yii::app()->request->baseUrl.ImageHelper::thumb($width,$height,$img, array('method' => 'resize'));
        return "<img src='$src' alt='$alt' class='$class'/>";
    }

    public function path($width, $height, $img)
    {
    	if ($img=="") {
        	$img = "/upload/noavatar.gif";
        }
    	$src = Yii::app()->request->baseUrl.ImageHelper::thumb($width,$height,$img, array('method' => 'resize'));
    	return $src;
    }
}