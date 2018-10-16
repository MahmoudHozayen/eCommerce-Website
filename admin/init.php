<?php
 
 	//DB Connection File

	require 'connect.php';

	//routes aka directories

		/*includes directories*/
		$templates	= 'includes/templates/';		 //templates directory

		$languages	= 'includes/languages/';		 //languages directory

		$functions 	= 'includes/functions/';		 //Functions Directories


		/*Layout directories*/
		$css 		= 'layout/css/';			 //css directory 

		$js			= 'layout/js/';			 	 //js directory

	include $functions . 'function.php';

	include $languages . 'en.php';

	include $templates . 'header.php';

	//only include navbar if $noNavbar var is not isset
	if (!isset($noNavbar)) {

		include $templates . 'navbar.php';
	}