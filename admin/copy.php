<?php 
	ob_start(); //output buffring Start
	session_start();
	$pageTitle = 'Members';
	if (isset($_SESSION['username'])) {
		include 'init.php';

		$goTo = '';
		if (isset($_GET['goTo'])) {

			$goTo = $_GET['goTo'];
		} else {
			
			$goTo = 'Mange';
		}

		if ($goTo == 'Mange') { 		// Manage Members Page

		} elseif ($goTo == 'Add') { 	//Add Member Page
		
		} elseif ($goTo == 'Insert') { 	//insert Member Page

		} elseif ($goTo == 'Edit') { 	//Edit Member Page

		} elseif ($goTo == 'Update') {	//Update Page

		} elseif ($goTo == 'Delete') { 	//Delet member page

		} elseif ($goTo == 'Activet') { //Activet Member Page


		} else {

		}
				
		include $templates . 'footer.php';
	} else {

		header('location: index.php');

		exit();
	}
	ob_end_flush(); //end output buffring