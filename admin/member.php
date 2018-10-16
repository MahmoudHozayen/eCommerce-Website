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
								// Manage Members Page
		if ($goTo == 'Mange') { 

			$query = '';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
				$query = 'AND RegStatus = 0';
			}

			//Selecting The Users From DataBase Except The Admin
			$stmt = $db->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
			//Execute the Prepared stmt
			$stmt->execute();
			//Fetching The Data
			$rows = $stmt->fetchAll();

			$count = $stmt->rowCount();
			?>
					<h1 class="text-center">Mange Members Page</h1>
					<div class="container">
						<div class="table-responsive">
							<table class="main-table text-center table table-bordered">
								<tr>
									<td>#ID</td>
									<td>Avatar</td>
									<td>Username</td>
									<td>E-Mail</td>
									<td>Full Name</td>
									<td>Registerd Date</td>
									<td>Control</td>
								</tr>
								<?php
									foreach ($rows as $row) {
										echo "<tr>";
											echo "<td>" . $row['UserID'] . "</td>";
											echo "<td>";
											if (empty($row['Avatar'])) {
											 	echo "No Image";
											} else {
												echo "<img src='uploads\Avatars\\" .$row['Avatar']."'" . "alt='" .$row['Username'] . "'>";
											}
											echo "</td>";
											echo "<td>" . $row['Username'] . "</td>";
											echo "<td>" . $row['Email'] . "</td>";
											echo "<td>" . $row['FullName'] . "</td>";
											echo "<td>" . $row['Date'] . "</td>";
											echo "<td>";
												echo "<a href='member.php?goTo=Edit&userid=" . $row['UserID'] ."'"." class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> ";
												echo "<a href='member.php?goTo=Delete&userid=" . $row['UserID'] ."'"." class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a> ";
												if ($row['RegStatus'] == 0) {
													echo "<a href='member.php?goTo=Activet&userid=" . $row['UserID'] ."'"." class='btn btn-info activet'><i class='fa fa-check'></i> Activet</a> ";
												}
											echo "</td>";
										echo "</tr>";
									}
								?>
							</table>
						</div>		
						<a href='member.php?goTo=Add' class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
					</div>		
<?php	} elseif ($goTo == 'Add') { //Add Member Page?>
					<h1 class="text-center">Add Member</h1>
					<div class="container">
						<form class="form-horizontal" method="POST" action="?goTo=Insert"  enctype="multipart/form-data">
							<!-- Start User Name Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Username :</label>
								<div class="col-sm-10">
									<input type="text" name="username" required="required" class="form-control" placeholder="Add Username">
								</div>
							</div>
							<!-- End User Name Field -->
							<!-- Start Password Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Password :</label>
								<div class="col-sm-10">
									<input type="password" name="password" required="required"  class="password form-control" autocomplete="new-password" placeholder="Add Strong Password ">
									<i class="fa fa-eye fa-x2 show-pass"></i>
								</div>
							</div>
							<!-- End Password Field -->	
							<!-- Start E-mail Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">E-mail :</label>
								<div class="col-sm-10">
									<input type="email" name="e-mail" required="required"  class="form-control" placeholder="Add E-Mail">
								</div>
							</div>
							<!-- End E-mail Field -->
							<!-- Start Full name Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Full Name :</label>
								<div class="col-sm-10">
									<input type="text" name="fullName" required="required"  class="form-control" placeholder="User FullName">
								</div>
							</div>
							<!-- End Avatar Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Avatar :</label>
								<div class="col-sm-10">
									<input type="file" name="avatar" required="required"  class="form-control">
								</div>
							</div>
							<!-- End Avatar Field -->								
							<!-- Start Submit Field -->
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Insert" class="btn btn-primary">
								</div>
							</div>
							<!-- End Submit Field -->										
						</form>
					</div>			
<?php	} elseif ($goTo == 'Insert') { //insert Member Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<div class='container'>";
				echo "<h1 class='text-center'>Insert Member</h1>";
				//Upload Files
				$avatar = $_FILES['avatar'];

				$avatarName  =  $_FILES['avatar']['name'];
				$avatarType  =  $_FILES['avatar']['type'];
				$avatarTmp   =  $_FILES['avatar']['tmp_name'];
				$avatarError =  $_FILES['avatar']['error'];
				$avatarSize  =  $_FILES['avatar']['size'];

				$avatarAllowedExtensions = array("png","jpeg","jpg","gif");

				$avatarExtenstion = strtolower(end(explode(".", $avatarName)));

				// Get The Variabales From The Form
				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['e-mail'];
				$name 	= $_POST['fullName'];

				$check = checkItem('Username', 'users', $user);

				$hashedPass = sha1($pass);
				// Erorr Handeling
				$formErorr = array(); 
				if (empty($user)) {
					$formErorr[] = '<strong>Username</strong> Is Empty';
				}
				if ($check > 0) {
					$formErorr[] = '<strong>Username Is Exist</strong> In DataBase !! Try Anthor One ';
				}				
				if (empty($email)) {
					$formErorr[] = '<strong>E-mail</strong> Is Empty';
				}
				if (empty($name)) {
					$formErorr[] = '<strong>FullName</strong> Is Empty';
				}
				if (empty($pass)) {
					$formErorr[] = '<strong>Password</strong> Is Empty';
				}				
				if (!empty($avatarName) && !in_array($avatarExtenstion, $avatarAllowedExtensions)) {
					$formErorr[] = 'This Extenstion Is <strong>Not Allowed';
				}
				if (empty($avatarName)) {
					$formErorr[] = 'Avatar Is <strong>Required</strong>';
				}
				if ($avatarSize > 4192304) {
					$formErorr[] = 'Avatar Must Be Less Than <strong>4MB</strong>';
				}							
				foreach ($formErorr as $erorr) {
					$theMsg = '<div class="alert alert-danger">' . $erorr . '</div>';
					redirectTo($theMsg, 'back', 5);

				}	

				if (empty($formErorr)) {
					// Avatar Upload
					$avatar = rand(0, 99999999999) . "_" . $avatarName;

					move_uploaded_file($avatarTmp, "uploads\Avatars\\" . $avatar);
					copy('uploads\Avatars\\' . $avatar, '..\uploads\Avatars\\' . $avatar);
					// Update The user Information
					$stmt = $db->prepare("	INSERT INTO 
													users (Username, Password, Email, FullName, RegStatus, Date, Avatar)
											VALUES 
													(:user, :pass, :Email, :FullName, 1, now(), :avatar)");
					$stmt->execute(array(
									':user'		=> $user ,
									':pass' 	=> $hashedPass, 
									':Email' 	=> $email,
									':FullName' => $name,
									':avatar' 	=> $avatar));
					// Success Mesage
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Inserted</div>";

					redirectTo($theMsg, 'back');
				}
				echo "</div>";		
			} else {
				echo "<div class='container'>";
				
				$theMsg = "<div class='alert alert-danger'>Sorry You can't Access This Page Dirctly</div>";
				
				redirectTo($theMsg);

				echo "</div>";
			}	
		} elseif ($goTo == 'Edit') { 	//Edit Member Page

				if (isset($_GET['userid']) && is_numeric($_GET['userid']) ) {

					$userid = intval($_GET['userid']);

					$stmt = $db->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1"); 

					//GroupID = 1 Mean He Is Admin //GroupID = 0 Mean He Is User

					$stmt->execute(array($userid));

					$row = $stmt->fetch(); //fetch the data from database

					$count = $stmt->rowCount();

					$check = checkItem('UserID', 'users', $userid);

					if ($count > 0 && $check > 0) { ?>
						<h1 class="text-center">Edit Member</h1>
						<div class="container">
							<form class="form-horizontal" method="POST" action="?goTo=Update"  enctype="multipart/form-data">
								<input type="hidden" name="userid" value="<?php echo $userid; ?>">
								<!-- Start User Name Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Username :</label>
									<div class="col-sm-10">
										<input type="text" name="username" value="<?php echo $row['Username'] ?>" required="required" class="form-control">
									</div>
								</div>
								<!-- End User Name Field -->
								<!-- Start Password Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Password :</label>
									<div class="col-sm-10">
										<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
										<input type="password" name="newpassword"  class="password form-control" autocomplete="new-password" placeholder="leave it blank if You don't Want To Change !!">
										<i class="fa fa-eye fa-x2 show-pass"></i>
									</div>
								</div>
								<!-- End Password Field -->	
								<!-- Start E-mail Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">E-mail :</label>
									<div class="col-sm-10">
										<input type="email" name="e-mail" value="<?php echo $row['Email'] ?>" required="required"  class="form-control">
									</div>
								</div>
								<!-- End E-mail Field -->
								<!-- Start Full name Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Full Name :</label>
									<div class="col-sm-10">
										<input type="text" name="fullName" value="<?php echo $row['FullName'] ?>" required="required"  class="form-control">
									</div>
								</div>
								<!-- End Full name Field -->
								<!-- End Avatar Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Avatar :</label>
									<div class="col-sm-10">
										<input type="file" name="avatar" class="form-control">
									</div>
								</div>
								<!-- End Avatar Field -->									
								<!-- Start Submit Field -->
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="save" class="btn btn-primary">
									</div>
								</div>
								<!-- End Submit Field -->										
							</form>
						</div>
			  <?php } else {
						echo "<div class='container'>";

						$theMsg = "<div class='alert alert-danger'>Sorry There Is No ID DataBase</div>";

						redirectTo($theMsg);

						echo "</div>";
					}
				} else {
						echo "<div class='container'>";

						$theMsg = "<div class='alert alert-danger'>Sorry There Is No ID In DataBase</div>";

						redirectTo($theMsg);

						echo "</div>";
				}  	
		} elseif ($goTo == 'Update') { 		//Update Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<div class='container'>";
				echo "<h1 class='text-center'>Update Member</h1>";

				//Upload Files
				$newAvatar = $_FILES['avatar'];



				if (!empty($newAvatar)) {
					$avatarName  =  $_FILES['avatar']['name'];
					$avatarType  =  $_FILES['avatar']['type'];
					$avatarTmp   =  $_FILES['avatar']['tmp_name'];
					$avatarError =  $_FILES['avatar']['error'];
					$avatarSize  =  $_FILES['avatar']['size'];

					$avatarAllowedExtensions = array("png","jpeg","jpg","gif");

					$avatarExtenstion = strtolower(end(explode(".", $avatarName)));						
				}		

				// Get The Variabales From The Form
				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['e-mail'];
				$name 	= $_POST['fullName'];

				// PassWord Trick
				$pass = '';
				if (empty($_POST['newpassword'])) {
					$pass = $_POST['oldpassword'];
				} else {
					$pass = sha1($_POST['newpassword']);
				}
				// Erorr Handeling
				$formErorr = array(); 
				if (empty($user)) {
					$formErorr[] = 'Username Is Empty';
				}
				if (empty($email)) {
					$formErorr[] = 'E-mail Is Empty';
				}
				if (empty($name)) {
					$formErorr[] = 'FullName Is Empty';
				}

				if (!empty($avatarName) && !in_array($avatarExtenstion, $avatarAllowedExtensions)) {
					$formErorr[] = 'This Extenstion Is <strong>Not Allowed';
				}



				if ($avatarSize > 4192304) {
					$formErorr[] = 'Avatar Must Be Less Than <strong>4MB</strong>';
				}					
				foreach ($formErorr as $erorr) {
					$theMsg = '<div class="alert alert-danger">' . $erorr . '</div>';
					redirectTo($theMsg, 'back');
				}	

				$stmt2 = $db->prepare(" SELECT
											* 
										FROM
											users
										WHERE
											Username = ?
										AND
											UserID	 != ?
										");
				$stmt2->execute(array($user, $id));

				$count = $stmt2->rowCount();

				if ($count == 1) {
					$theMsg = '<div class="alert alert-danger">' . "Username Is Exist !" . '</div>';
					redirectTo($theMsg, 'back');
				} 
				if (empty($formErorr)) {

					if (!empty($avatarName)) {
						// Avatar Upload
						$avatar = rand(0, 99999999999) . "_" . $avatarName;

						move_uploaded_file($avatarTmp, "uploads\Avatars\\" . $avatar);
						copy('uploads\Avatars\\' . $avatar, '..\uploads\Avatars\\' . $avatar);						
					} else {
						$allAvatars = getAll("*", "users", "WHERE UserID = {$_POST['userid']}", "", "UserID", "ASC", "");
						foreach ($allAvatars as $oldavatar) {
							$avatar = $oldavatar['Avatar'];
						}
					}
					
					// Update The user Information
					$stmt = $db->prepare("UPDATE users SET Username = ?, Password = ? , Email = ?, FullName = ?, Avatar = ? WHERE UserID = ?");
					$stmt->execute(array($user, $pass , $email, $name, $avatar, $id));

					// Success Mesage
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Updated</div>';

					echo "<div class='container'>";

					redirectTo($theMsg);

					echo "</div>";				
				}						
			} else {
				echo "<h1 class='text-center'>Update Member</h1>" . '<br>';
				$theMsg = "<div class='alert alert-danger'>Sorry You can't Access This Page Dirctly</div>";

				redirectTo($theMsg);
			}
		} elseif ($goTo == 'Delete') { //Delet member page
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Delete Member</h1>";

			if (isset($_GET['userid']) && is_numeric($_GET['userid']) ) {
				$userid = intval($_GET['userid']);

				//$stmt = $db->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1'); 

				//$stmt->execute(array($userid));

				//$count = $stmt->rowCount();

				$check = checkItem('UserID', 'users', $userid);

				if ($check > 0) {

					$stmt = $db->prepare("DELETE FROM users WHERE UserID = :userid"); 

					$stmt->bindParam(':userid', $userid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Deleted</div>';

					redirectTo($theMsg, 'back', 2);
				} else {
					$theMsg = "<div class='alert alert-danger'>there is No ID To Delete</div>";
					redirectTo($theMsg, 'back');					
				}
			}
			echo "</div>";
		} elseif ($goTo == 'Activet') { //Activet Member Page
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Activet Member</h1>";

			if (isset($_GET['userid']) && is_numeric($_GET['userid']) ) {
				$userid = intval($_GET['userid']);

				//$stmt = $db->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1'); 

				//$stmt->execute(array($userid));

				//$count = $stmt->rowCount();

				$check = checkItem('UserID', 'users', $userid);

				if ($check > 0) {

					$stmt = $db->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?"); 

					$stmt->execute(array($userid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Activeted</div>';

					redirectTo($theMsg, 'back');
				} else {
					$theMsg = "<div class='alert alert-danger'>there is No ID To Delete</div>";
					redirectTo($theMsg, 'back');					
				}
			}
			echo "</div>";

		} else {
			echo "<div class='container'>";
			echo "<h1 class='text-center'>" . "Sorry" . "</h1>";
			$theMsg = "<div class='alert alert-danger'>Sorry there's No Page in This Name</div>";
			redirectTo($theMsg);
		}
				
		include $templates . 'footer.php';
	} else {

		header('location: index.php');

		exit();
	}
		ob_end_flush(); //end output buffring
