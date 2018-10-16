<?php 
	ob_start();
	session_start();
	$pageTitle = 'Login | Signup';	
	
	include 'init.php';


	if (isset($_SESSION['Username'])) {
		header('Location: index.php');
		exit();
	}
	$singupAndLoginFormError = array();  // array To Handel The Form Errors	Messages
	if (isset($_POST['login'])) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$username 	= $_POST['username'];

			$password   = $_POST['password'];

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
										Password = ?'); 
			$stmt->execute(array($username, $hashedPass));
			$row = $stmt->fetch(); //fetch the data from database
			$count = $stmt->rowCount();

			if ($count > 0) {
				$_SESSION['Username'] 		= $username;
				$_SESSION['Uid']			= $row['UserID'];
				header('location: index.php');
				exit();
			} else {
				$singupAndLoginFormError[] = "You Are Not Registered Member";
			}
			
		}
	} else {
		
		
		//SignUp Username Check		
		if (isset($_POST['username'])) {

			$username 	= $_POST['username'];

			$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

			$checkUserExist = checkItem('Username', 'users', $filterdUser);

			if (strlen($filterdUser) < 5) {

				$singupAndLoginFormError[] = "Username Must Be More Than 5 Characters";

			}

			if ($checkUserExist == 1) {

				$singupAndLoginFormError[] = "Sorry This User Is Exist";

			}			
		}	

		//SignUp Password Check		
		if (isset($_POST['password']) && isset($_POST['password2'])) {

			$password 	= $_POST['password'];
			$password2	= $_POST['password2'];

			if (empty($password) || empty($password2)) {
				$singupAndLoginFormError[] = "Passwords Cannot Be Empty";
			}

			if (sha1($password) !== sha1($password2)) {

				$singupAndLoginFormError[] = "Passwords Dosen't Match";
			}
		}
		//SignUp Email Check		
		if (isset($_POST['email'])) {

			$email 		= $_POST['email'];

			$filterdEmail= filter_var($email, FILTER_SANITIZE_EMAIL);

			if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

				$singupAndLoginFormError[] = "Email Not Valid";
			}
		}

		//Upload Files
		if (isset($_FILES['avatar'])) {

			$avatarName  =  $_FILES['avatar']['name'];
			$avatarType  =  $_FILES['avatar']['type'];
			$avatarTmp   =  $_FILES['avatar']['tmp_name'];
			$avatarError =  $_FILES['avatar']['error'];
			$avatarSize  =  $_FILES['avatar']['size'];

			$avatarAllowedExtensions = array("png","jpeg","jpg","gif");

			$imgNameArray = explode(".", $avatarName);

			$avatarExtenstion = strtolower(end($imgNameArray));

			if (!empty($avatarName) && !in_array($avatarExtenstion, $avatarAllowedExtensions)) {
				$singupAndLoginFormError[] = 'This Extenstion Is <strong>Not Allowed';
			}
			if (empty($avatarName)) {
				$singupAndLoginFormError[] = 'Avatar Is <strong>Required</strong>';
			}
			if ($avatarSize > 4192304) {
				$singupAndLoginFormError[] = 'Avatar Must Be Less Than <strong>4MB</strong>';
			}			
		}


		//Add User To Database
		if (empty($singupAndLoginFormError) && isset($_POST['signup'])) {

			$avatar = rand(0, 99999999999) . "_" . $avatarName;

			move_uploaded_file($avatarTmp, "uploads\Avatars\\" . $avatar);

			copy('uploads\Avatars\\' . $avatar, 'admin\uploads\Avatars\\' . $avatar);

			$insertStmt = $db->prepare('INSERT INTO users(Username, Password, Email, RegStatus, Date, Avatar) VALUES 
				(:user, :pass, :email, 0, now(), :avatar ) ');
			$insertStmt->execute(array(
				':user' 	=> $filterdUser,
				':pass' 	=> sha1($password),
				':email'	=> $filterdEmail,
				':avatar'	=> $avatar
			 ));
			$successMesage = "Congratulations ";
		}
	}
?>

	<div class="container login-page">
		<h1 class="text-center">
			<span class="selected" data-class="login">Login</span> | 
			<span data-class="signup">Signup</span>
		</h1>
		<!-- Start Login Form -->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="input-container">
				<input 
					class="form-control" 
					type="text" 
					name="username" 
					autocomplete="off"
					placeholder="Type your username" 
					required />
			</div>
			<div class="input-container">
				<input 
					class="form-control" 
					type="password" 
					name="password" 
					autocomplete="new-password"
					placeholder="Type your password" 
					required />
			</div>
			<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
		</form>
		<!-- End Login Form -->
		<!-- Start Signup Form -->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
			<div class="input-container">
				<input 
					class="form-control" 
					type="text" 
					name="username" 
					autocomplete="off"
					placeholder="username"
					pattern=".{4,}"
					title="Username Must Be Between 4 Chars" 
					required />
			</div>
			<div class="input-container">
				<input 
					class="form-control" 
					type="password" 
					name="password" 
					autocomplete="new-password"
					placeholder="password"
					minlength="8" 
					required />
			</div>
			<div class="input-container">
				<input 
					class="form-control" 
					type="password" 
					name="password2" 
					autocomplete="new-password"
					placeholder="password again"
					minlength="8" 
					required />
			</div>
			<div class="input-container">
				<input 
					class="form-control" 
					type="email" 
					name="email" 
					placeholder="email" 
					required/>
			</div>
			<!-- End Avatar Field -->
			<div class="form-group">
				<div class="signup-img-input">
					<span class="text-center">Upload Your Image</span>
					<input class="input-container" type="file" name="avatar" class="form-control">
				</div>	
			</div>
			<!-- End Avatar Field -->			
			<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
		</form>
		<!-- End Signup Form -->
		<div class="text-center messages">
			<?php
				if (! empty($singupAndLoginFormError)) {
					foreach ($singupAndLoginFormError as $error) {
						echo '<div class="error-msg">' . $error . '</div>';
					}
				}
			?>
			<?php
				if (! empty($successMesage)) {
					echo '<div class="success-msg">' . $successMesage . '</div>';	
				}
			?>
		</div>
	</div>

<?php 
include $templates . 'footer.php'; 

ob_end_flush();
?>