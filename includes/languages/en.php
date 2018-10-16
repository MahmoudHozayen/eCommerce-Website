<?php

	function lang($phrase) {

		static $lang = array(
			//Navbar Links
			'HOME' 			=> 'Home', 

			'CATEGORIES' 	=> 'Categories', 
			'ITEM' 			=> 'Items', 
			'MEMBER' 		=> 'Members',
			'COMMENTS' 		=> 'Comments',
			'STATS' 		=> 'Statistics', 
			'LOG' 			=> 'Logs', 

			'EDIT' 			=> 'Edit Profile',

			'SETTINGS' 		=> 'Settings',

			'OUT' 			=> 'LogOut',

		);

		return $lang[$phrase];
	}