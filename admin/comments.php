<?php 
	ob_start(); //output buffring Start
	session_start();
	$pageTitle = 'Comments';
	if (isset($_SESSION['username'])) {
		include 'init.php';

		$goTo = '';
		if (isset($_GET['goTo'])) {

			$goTo = $_GET['goTo'];
		} else {
			
			$goTo = 'Mange';
		}
								// Manage Comments Page
		if ($goTo == 'Mange') { 


			//Selecting The Users From DataBase Except The Admin
			$stmt = $db->prepare("	SELECT 
										comments.*,
										items.Name AS item_name,
										users.Username AS Member
									From
									 	comments 
									INNER JOIN 
										items
									ON 
										comments.item_id = items.Item_ID
									INNER JOIN 
										users
									ON 
										comments.user_id = users.UserID	
									ORDER BY 
										comments.c_id
									ASC									
									");
			//Execute the Prepared stmt
			$stmt->execute();
			//Fetching The Data
			$rows = $stmt->fetchAll();

			$count = $stmt->rowCount();
			?>
					<h1 class="text-center">Mange Comments Page</h1>
					<div class="container">
						<div class="table-responsive">
							<table class="main-table text-center table table-bordered">
								<tr>
									<td>#ID</td>
									<td>Comments</td>
									<td>User Name</td>
									<td>Item Name</td>
									<td>Comment date</td>
									<td>Control</td>
								</tr>
								<?php
									foreach ($rows as $row) {
										echo "<tr>";
											echo "<td>" . $row['c_id'] . "</td>";
											echo "<td>" . $row['comment'] . "</td>";
											echo "<td>" . $row['Member'] . "</td>";
											echo "<td>" . $row['item_name'] . "</td>";
											echo "<td>" . $row['comment_date'] . "</td>";
											echo "<td>";
												echo "<a href='comments.php?goTo=Edit&commentid=" . $row['c_id'] ."'"." class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> ";
												echo "<a href='comments.php?goTo=Delete&commentid=" . $row['c_id'] ."'"." class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a> ";
												if ($row['status'] == 0) {
													echo "<a href='comments.php?goTo=Approve&commentid=" . $row['c_id'] ."'"." class='btn btn-info activet'><i class='fa fa-check'></i> Activet</a> ";
												}
											echo "</td>";
										echo "</tr>";
									}
								?>
							</table>
						</div>		
					</div>		
<?php	} elseif ($goTo == 'Edit') { 	//Edit Comments Page

				if (isset($_GET['commentid']) && is_numeric($_GET['commentid']) ) {

					$commentid = intval($_GET['commentid']);

					$stmt = $db->prepare("SELECT * FROM Comments WHERE c_id = ? LIMIT 1"); 

					$stmt->execute(array($commentid));

					$row = $stmt->fetch(); //fetch the data from database

					$count = $stmt->rowCount();

					$check = checkItem('c_id', 'comments', $commentid);

					if ($count > 0 && $check > 0) { ?>
						<h1 class="text-center">Edit Coments</h1>
						<div class="container">
							<form class="form-horizontal" method="POST" action="?goTo=Update" >
								<input type="hidden" name="commentid" value="<?php echo $commentid; ?>">
								<!-- Start Comment Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Comment :</label>
									<div class="col-sm-10">
										<textarea name="comment" required="required" class="form-control"><?php echo $row['comment'] ?></textarea>
									</div>
								</div>
								<!-- End Comment Field -->
								<!-- Start Status Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Status :</label>
									<div class="col-sm-10">
										<input type="number" name="status" value="<?php echo $row['status'] ?>">
									</div>
								</div>
								<!-- End Status Field -->	
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
		} elseif ($goTo == 'Update') { 		//Update comments Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<div class='container'>";
				echo "<h1 class='text-center'>Update Comment</h1>";

				// Get The Variabales From The Form
				$commentid 	= $_POST['commentid'];
				$comment 	= $_POST['comment'];
				$status 	= $_POST['status'];

				$check = checkItem('c_id', 'comments', $commentid);
				
				// Erorr Handeling
				$formErorr = array(); 
				if (empty($comment)) {
					$formErorr[] = 'comment Is Empty';
				}
				if ($status > 1 || $status < 0) {
					$formErorr[] = 'status Is Not In Range 1 And 0';
				}				
				if ($check != 1) {
					$formErorr[] = 'Ther is No Comment In This ID';
				}
				foreach ($formErorr as $erorr) {
					$theMsg = '<div class="alert alert-danger">' . $erorr . '</div>';
					redirectTo($theMsg, 'back');
				}	



				if (empty($formErorr)) {
					// Update The Comment Information
					$stmt = $db->prepare("	UPDATE 
												comments 
											SET 
												comment = ?,
												status = ? 
											 WHERE 
											 	c_id = ?");
					$stmt->execute(array($comment, $status , $commentid));

					// Success Mesage
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Updated</div>';

					echo "<div class='container'>";

					redirectTo($theMsg);

					echo "</div>";					
				}						
			} else {
				echo "<h1 class='text-center'>Update Comment</h1>" . '<br>';
				$theMsg = "<div class='alert alert-danger'>Sorry You can't Access This Page Dirctly</div>";

				redirectTo($theMsg);
			}
		} elseif ($goTo == 'Delete') { //Delet Comment page
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Delete Comment</h1>";

			if (isset($_GET['commentid']) && is_numeric($_GET['commentid']) ) {
				$commentid = intval($_GET['commentid']);

				$check = checkItem('c_id', 'comments', $commentid);

				if ($check > 0) {

					$stmt = $db->prepare("DELETE FROM comments WHERE c_id = :commentid"); 

					$stmt->bindParam(':commentid', $commentid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Deleted</div>';

					redirectTo($theMsg, 'back', 2);
				} else {
					$theMsg = "<div class='alert alert-danger'>there is No ID To Delete</div>";
					redirectTo($theMsg, 'back');					
				}
			}
			echo "</div>";
		} elseif ($goTo == 'Approve') { //Activet Comment Page
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Approve Comment</h1>";

			if (isset($_GET['commentid']) && is_numeric($_GET['commentid']) ) {
				$commentid = intval($_GET['commentid']);

				$check = checkItem('c_id', 'comments', $commentid);

				if ($check > 0) {

					$stmt = $db->prepare("UPDATE comments SET status = 1 WHERE c_id = ?"); 

					$stmt->execute(array($commentid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Approved</div>';

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
			redirectTo($theMsg, "", 1);
		}
				
		include $templates . 'footer.php';
	} else {

		header('location: index.php');

		exit();
	}

	ob_end_flush(); //end output buffring
