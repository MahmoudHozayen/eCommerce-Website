<?php
	ob_start();
	header('Location: ../index.php');
	exit();
	ob_end_flush(); //end output buffring

?>