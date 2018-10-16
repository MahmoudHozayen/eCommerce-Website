<?php

	//Error Reporting
 	ini_set("display_errors", "On");
 	error_reporting(E_ALL);
 	
 	//DB Connection File

	require 'admin/connect.php';

	//User Session 
	$UserSession = '';

	if (isset($_SESSION['Username'])) {
		$UserSession = $_SESSION['Username'];
	}

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