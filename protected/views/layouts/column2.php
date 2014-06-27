<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
</head>
<body>
	
	<div class="container">
		<div class="row">
			<div class="span12">
				<?php 
	    $this->widget('bootstrap.widgets.TbNavbar', array(
		    'brand' => 'Title',
		    'brandOptions' => array('style'=>'width:auto;margin-left: 0px;display:inline-block;'),
		    'fixed' => 'top',
		    'htmlOptions' => array('style' => 'position:absolute'),
		    'items' => array(
		    				array(
							    'class' => 'bootstrap.widgets.TbMenu',
							    'items' => array(
								    array('label' => 'Home', 'url' => '#', 'active' => true),
								    array('label' => 'Link', 'url' => '#'),
								    array('label' => 'Link', 'url' => '#'),
		    					)
		    				)
		    			)
		    ));	
	 ?>		
			</div>
		</div>

		
		<div class="row">
			<!-- <div class="span2">
				45t54t54t54
			</div> -->
			<div class="span12">
				<?php echo $content; ?>		
			</div>
		</div>
		
	</div> 

</body>
