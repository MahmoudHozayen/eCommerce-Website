<?php

	$dsn 	= 'mysql:host=localhost;dbname=store'; //data source name 

	$user	= 'root'; //user to connect to DB

	$pass	= '';	//user's password to Connect To Db

	$option = array(

		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
	);  // Array Of Options to excute on Connecting

	try {  //try To Connetct 

		$db = new PDO($dsn, $user, $pass, $option);

		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e){ //catch Error {Error Mode EXCEPTION}

		echo "Failed To Connect" . $e->getMessage(); //Printing The Error
	}