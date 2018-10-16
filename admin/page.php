<?php

	$goTo = '';

	if (isset($_GET['goTo'])) {

		$goTo = $_GET['goTo'];
	} else {
		
		$goTo = 'Mange';
	}

	
	if ($goTo == 'Mange') {
		echo "You Are In Mange Page";
	} elseif ($goTo == 'Edit') {
		echo "You Are In Edit Page";
	} elseif ($goTo == 'update') {
		echo "You Are In update Page";
	} elseif ($goTo == 'insert') {
		echo "You Are In insert Page";
	} elseif ($goTo == 'Add') {
		echo "You Are In Add Page";
	} elseif ($goTo == 'delete') {
		echo "You Are In delete Page";
	} elseif ($goTo == 'stats') {
		echo "You Are In stats Page";
	} else {
		echo "Sorry there's No Page in This Name";
	}