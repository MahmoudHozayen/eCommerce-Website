<?php 
	ob_start(); 

	session_start();
	$noNavbar 	= '';
	$pageTitle 	= 'Login';
	if (isset($_SESSION['username'])) {
		header('location: dashboard.php');
		exit();
	}
?>
<?php include 'init.php'; ?>
<?php

	//Check If The User Coming From HTTP "Post" Request Method
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password);

		//Check If The User Exist in Database
		$stmt = $db->prepare('
								SELECT 
									UserID, Username, Password 
								FROM 
									users 
								WHERE 
									Username = ? 
								AND 
									Password = ? 
								AND 
									GroupID = 1
								LIMIT 1'); 
		//GroupID = 1 Mean He Is Admin //GroupID = 0 Mean He Is User
		$stmt->execute(array($username, $hashedPass));
		$row = $stmt->fetch(); //fetch the data from database
		$count = $stmt->rowCount();

		//if count > 0 This Mean that DB Contain Record About This UserName and Password
		if ($count > 0) {
			$_SESSION['username'] = $username;	//register user name to sesstion
			$_SESSION['ID'] = $row['UserID'];	//register user ID to sesstion
			header('location: dashboard.php');  //redirct To dashboard.php
			exit();
		}
	}
?>

	<form 
		class="login" 
		method="POST" 
		action="<?php echo $_SERVER['PHP_SELF']; //Send The info to same page ?>"
	>
		<h3 class="text-center">Admin Login</h3>

		<input class="form-control" type="text" name="user" placeholder="User Name" autocomplete="off">

		<input  class="form-control" 
				type="password" 
				name="pass" 
				placeholder="Password" 
				autocomplete="new-password"
		>

		<input class="btn btn-primary btn-block" type="submit" value="Login">
	</form>
<?php 
	include $templates . 'footer.php'; 
	ob_end_flush(); //end output buffring
?>