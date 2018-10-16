<?php

	/*
	** getAll() Function V2.0 Created By : Mahmoud Hozayen
	** Function get All Items In Database
	** Accept  parameters : [$selectedField = '*'], [$tableName], [$where = NULL], [$orderField = NULL], [$orderType = 'ASC']	
	** 
	**
	*/
	function getAll($selectedField, $tableName, $where = NULL, $and = NULL, $orderField = NULL, $orderType = 'ASC', $limit = NULL){
		global $db;

		if (empty($limit)) {
			$limit = 99999999999999;
		}
		$getAllStmt = $db->prepare("
									SELECT 
										$selectedField 
									FROM 
										$tableName 
									$where $and
									ORDER BY 
										$orderField $orderType 
									LIMIT $limit
									");
		$getAllStmt->execute();

		$All = $getAllStmt->fetchALL();

		return $All;
	}



	/*
	** getTitle() Function Created To Type The Title OF The Page If The Page has Variable $pageTitle
	** and if the varibale not set type a Defualt Title
	** getTitle() V 1.0 Created By : Mahmoud Hozayen
	*/

	function getTitle() {
		global $pageTitle;

		if (isset($pageTitle)) {
			
			echo $pageTitle;
		} else {
			echo 'Defualt';
		}
	}

	/*
	** getUserName() V 1.0 Created By : Mahmoud Hozayen
	** Function Created To Type username on NavBar 
	*/
	
	function getUserName(){

		$username = $_SESSION['username'] . ' ';

		echo ucfirst($username); //ucfirst make the first char To UpperCase
	}

	/*
	** redirectTo($theMsg, $url = Null, $seconds = 3) Function V 1.0 Created By : Mahmoud Hozayen
	** Function To reDirect To previous page or Home Page
	** Accept 3 parameters : $theMsg, $url = Null, $seconds = 3
	** [$theMsg - The message that Will Show While ReDircting]
	** [$url - by default it is null and will redirect to Home Page but you can add "back" and will re direct to previous page]
	** [$seconds - time to redirecting in seconds]
	*/
	function redirectTo($theMsg, $url = Null, $seconds = 3) {
		if ($url == Null) {
			$url = 'index.php';
			$link = 'Home';
		} else {
			if (isset($_SERVER['HTTP_REFERER'])) {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'Back';
			} else {
				$url = 'index.php';
				$link = 'Home';
			}
		}
		echo $theMsg;
		echo '<div class="alert alert-info">' . 'You Will Redirect To ' . $link . ' After : ' . $seconds . '</div>';
		header("refresh:$seconds;url=$url");
		exit();
	}

	/*
	** checkItem($select, $from, $value) function V1.0 Created By : Mahmoud Hozayen
	** Function To Check If The item In Exist DataBase
	** Accept 3 parameters : $select, $from, $value
	** [$select => Selected Item]
	** [$From => Selected Table]
	** [$value => any value Wanted on table ]
	*/
	function checkItem($select, $from, $value) {
		global $db;

		$statment = $db->prepare("SELECT $select FROM $from WHERE $select = ?");

		$statment->execute(array($value));

		$counter = $statment->rowCount();

		return $counter;
	}
	/*
	** countItems($item, $table) Function V1.0 Created By : Mahmoud Hozayen
	** Function To Count any items in Database
	** Accept 2 parameters : $item, $table	
	** [$item - The row that you want to count]
	** [$table - The table That containe the item/row]
	*/
	function countItems($item, $table) {
		global $db;

		$statment1 = $db->prepare("SELECT COUNT($item) FROM $table");

		$statment1->execute();

		return $statment1->fetchColumn();
	}

	/*
	** getLatest() Function V1.0 Created By : Mahmoud Hozayen
	** Function To Get The Latest Items In Database
	** Accept  parameters : 	
	**
	**
	*/
	function getLatest($select, $table, $order, $limit = 5) {
		global $db;

		$getStmt = $db->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$getStmt->execute();

		$rows = $getStmt->fetchALL();

		return $rows;
	}