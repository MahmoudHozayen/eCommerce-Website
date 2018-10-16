<?php 
	ob_start(); //output buffring Start
	session_start();
	$pageTitle = 'Items';
	if (isset($_SESSION['username'])) {
		include 'init.php';

		$goTo = '';
		if (isset($_GET['goTo'])) {

			$goTo = $_GET['goTo'];
		} else {
			
			$goTo = 'Mange';
		}

		if ($goTo == 'Mange') { 		// Manage Item Page


			//Selecting The Users From DataBase Except The Admin
			$stmt = $db->prepare("	SELECT 
										items.*, 
										categories.Name AS category_name, 
										categories.ID AS category_id, 
										users.Username 
									FROM 
										items
									INNER JOIN 
										categories 
									ON 
										categories.ID = items.Cat_ID 
									INNER JOIN 
										users 
									ON 
										users.UserID = items.Member_ID");			
			//Execute the Prepared stmt
			$stmt->execute();
			//Fetching The Data
			$items = $stmt->fetchAll();

			$count = $stmt->rowCount();
			?>
					<h1 class="text-center">Mange Items Page</h1>
					<div class="container">
						<div class="table-responsive">
							<table class="main-table text-center table table-bordered">
								<tr>
									<td>#ID</td>
									<td>Name</td>
									<td>Image</td>
									<td>Description</td>
									<td>Price</td>
									<td>Adding Date</td>
									<td>Category</td>
									<td>Username</td>
									<td>Control</td>
								</tr>
								<?php
									foreach ($items as $item) {
										echo "<tr>";
											echo "<td>" . $item['Item_ID'] . "</td>";
											echo "<td><a target='_blank' class='custom-link' href='../items.php?itemid=".$item['Item_ID']."'>" . $item['Name'] . "</a></td>";
											
											echo "<td><img src='uploads\Item_Img\\";
												if (!empty($item['Item_Img'])) {
												 	echo $item['Item_Img'];
												} else {
													echo "img.png";
												} 
											echo "' alt='". $item['Name'] ."'></td>";

											echo "<td>" . $item['Description'] . "</td>";
											echo "<td>" . $item['Price'] . "</td>";
											echo "<td>" . $item['Add_Date'] . "</td>";
											echo "<td><a target='_blank' class='custom-link' href='../categories.php?pageid=". $item['category_id']."'>" . $item['category_name'] . "</a></td>";
											echo "<td>" . $item['Username'] . "</td>";
											echo "<td>";
												echo "<a href='items.php?goTo=Edit&itemid=" . $item['Item_ID'] ."'"." class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> ";
												echo "<a href='items.php?goTo=Delete&itemid=" . $item['Item_ID'] ."'"." class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a> ";
												if ($item['Approval_Status'] == 0) {
													echo "<a href='items.php?goTo=Approve&itemid=" . $item['Item_ID'] ."'"." class='btn btn-info activet'><i class='fa fa-check'></i> Approve</a> ";
												}
											echo "</td>";
										echo "</tr>";
									}
								?>
							</table>
						</div>		
						<a href='items.php?goTo=Add' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Item</a>
					</div>	

<?php		} elseif ($goTo == 'Add') { 	//Add Item Page ?>
					<h1 class="text-center">Add New Item</h1>
					<div class="container add-item">
						<form class="form-horizontal" method="POST" action="?goTo=Insert" enctype="multipart/form-data">
							<!-- Start Item Name Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Name :</label>
								<div class="col-sm-10 col-md-6">
									<input 
											type="text" 
											name="name" 
											required="required" 
											class="form-control" 
											placeholder="Add Item Name">
								</div>
							</div>
							<!-- End Item Name Field -->
							<!-- Start Discription Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Discription :</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="discription"
										required="required" 										  
										class="form-control" 
										placeholder="Discription Of The Item ">
								</div>
							</div>
							<!-- End Discription Field -->
							<!-- Start Price Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Price :</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="price"
										required="required" 										  
										class="form-control" 
										placeholder="Price Of The Item $">
								</div>
							</div>
							<!-- End Price Field -->
							<!-- Start Country Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Country :</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="country"
										required="required" 										  
										class="form-control" 
										placeholder="Country Of The Item ">
								</div>
							</div>
							<!-- End Country Field -->	
							<!-- Start Status Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Status :</label>
								<div class="col-sm-10 col-md-6">
									<select name="status">
										<option value="0">....</option>
										<option value="1">New</option>
										<option value="2">Like New</option>
										<option value="3">Used</option>
										<option value="3">Old</option>
									</select>
								</div>
							</div>
							<!-- End Status Field -->
							<!-- Start Member Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Member :</label>
								<div class="col-sm-10 col-md-6">
									<select name="member">
										<option value="0">....</option>
										<?php
											$allMembers = getAll("*", "users", "WHERE RegStatus = 1", "", "UserID",  "ASC","");
											foreach ($allMembers as $user) {
												echo "<option value=".$user['UserID'].">".$user['Username']."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Member Field -->
							<!-- Start Category Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Category :</label>
								<div class="col-sm-10 col-md-6">
									<select name="category">
										<option value="0">....</option>
										<?php
											$allCats = getAll("*", "categories", "WHERE Parent = 0", "", "ID",  "ASC","");
											foreach ($allCats as $cat) {
												echo "<option value=".$cat['ID'].">".$cat['Name']."</option>";

												$subCats = getAll("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID",  "ASC","");
												foreach ($subCats as $sub) {
													echo "<option value=".$sub['ID'].">--- ".$sub['Name']."</option>";
												}												
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Category Field -->
							<!-- End Avatar Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Item Img :</label>
								<div class="col-sm-10 col-md-6">
									<input type="file" name="Item_Img" required="required"  class="form-control">
								</div>
							</div>
							<!-- End Avatar Field -->								
							<!-- Start Tags Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Tags :</label>
								<div class="col-sm-10 col-md-6">
									<div data-tags-input-name="tags" id="tagBox"></div>
								</div>
							</div>
							<!-- End Tags Field -->																																			
							<!-- Start Submit Field -->
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Add Item" class="btn btn-primary btn-sm">
								</div>
							</div>
							<!-- End Submit Field -->										
						</form>
					</div>			
<?php	} elseif ($goTo == 'Insert') { 	//insert Item Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<div class='container'>";
				echo "<h1 class='text-center'>Insert Item</h1>";

				//Item img File
				if(isset($_FILES['Item_Img'])) {
					$avatarName  =  $_FILES['Item_Img']['name'];
					$avatarType  =  $_FILES['Item_Img']['type'];
					$avatarTmp   =  $_FILES['Item_Img']['tmp_name'];
					$avatarError =  $_FILES['Item_Img']['error'];
					$avatarSize  =  $_FILES['Item_Img']['size'];
					
					$avatarAllowedExtensions = array("png","jpeg","jpg","gif");

					$Extenstion = explode(".", $avatarName);
					$avatarExtenstion = strtolower(end($Extenstion));
				}

				// Get The Variabales From The Form
				$name 		= $_POST['name'];
				$disc 		= $_POST['discription'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$status 	= $_POST['status'];
				$member 	= $_POST['member'];
				$category 	= $_POST['category'];
				$tags		= implode(", ", $_POST['tags']);

				// Erorr Handeling
				$formErorr = array(); 
				if (empty($name)) {
					$formErorr[] = 'Item Name Is Empty';
				}				
				if (empty($disc)) {
					$formErorr[] = 'Discription Is required';
				}
				if (empty($price)) {
					$formErorr[] = 'Price Is required And Must Be A Number';
				}
				if (empty($country)) {
					$formErorr[] = 'Country Is required';
				}
				if ($status == 0) {
					$formErorr[] = 'Status Must Be Greater Than 0 ';
				}	
				if ($member == 0) {
					$formErorr[] = 'member Must Be Greater Than 0 ';
				}	
				if ($category == 0) {
					$formErorr[] = 'category Must Be Greater Than 0 ';
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

				if (empty($formErorr)) {

					if (!empty($avatarName)) {
						$avatar = rand(0, 99999999999) . "_" . $avatarName;

						move_uploaded_file($avatarTmp, "uploads\Item_Img\\" . $avatar);
						//copy the img from admin to front end
						copy('uploads\Item_Img\\' . $avatar, '..\uploads\Item_Img\\' . $avatar);
					} else {
						$avatar = "";
					}


					// Add The Item Information
					$stmt = $db->prepare("	INSERT INTO 
													items (Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID, Tags, Item_Img)
											VALUES 
													(:name, :disc, :price, :country, :status, now(), :member, :category, :tags, :img)");
					$stmt->execute(array(
									':name' 	=> $name ,
									':disc' 	=> $disc, 
									':price' 	=> $price, //$ Sign is Added After check If the Price is numeric
									':country' 	=> $country,
									':status' 	=> $status,
									':member' 	=> $member,
									':category' => $category,
									':tags' 	=> $tags,
									':img' 		=> $avatar));
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
		} elseif ($goTo == 'Edit') { 	//Edit Item Page

				if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) ) {

					$itemid = intval($_GET['itemid']);

					$stmt = $db->prepare("SELECT * FROM items WHERE Item_ID = ?"); 

					//GroupID = 1 Mean He Is Admin //GroupID = 0 Mean He Is User

					$stmt->execute(array($itemid));

					$item_Fetched = $stmt->fetch(); //fetch the data from database

					$count = $stmt->rowCount();

					$check = checkItem('Item_ID', 'items', $itemid);

					if ($count > 0 && $check > 0) { ?>
						<h1 class="text-center">Edit Item</h1>
						<div class="container add-item">
							<form class="form-horizontal" method="POST" action="?goTo=Update" enctype="multipart/form-data">
								<input type="hidden" name="itemid" value="<?php echo $itemid; ?>">								
								<!-- Start Item Name Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Name :</label>
									<div class="col-sm-10 col-md-6">
										<input 
												type="text" 
												name="name" 
												required="required" 
												class="form-control" 
												placeholder="Add Item Name"
												value="<?php echo $item_Fetched['Name'] ?>">
									</div>
								</div>
								<!-- End Item Name Field -->
								<!-- Start Discription Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Discription :</label>
									<div class="col-sm-10 col-md-6">
										<input 
											type="text" 
											name="discription"
											required="required" 										  
											class="form-control" 
											placeholder="Discription Of The Item "
											value="<?php echo $item_Fetched['Description'] ?>">
									</div>
								</div>
								<!-- End Discription Field -->
								<!-- Start Price Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Price :</label>
									<div class="col-sm-10 col-md-6">
										<input 
											type="text" 
											name="price"
											required="required" 										  
											class="form-control" 
											placeholder="Price Of The Item $"
											value="<?php echo $item_Fetched['Price'] ?>">
									</div>
								</div>
								<!-- End Price Field -->
								<!-- Start Country Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Country :</label>
									<div class="col-sm-10 col-md-6">
										<input 
											type="text" 
											name="country"
											required="required" 										  
											class="form-control" 
											placeholder="Country Of The Item "
											value="<?php echo $item_Fetched['Country_Made'] ?>">
									</div>
								</div>
								<!-- End Country Field -->	
								<!-- Start Status Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Status :</label>
									<div class="col-sm-10 col-md-6">
										<select name="status">
											<option value="1" <?php if ($item_Fetched['Status'] == 1) {echo "Selected";} ?>>New</option>
											<option value="2" <?php if ($item_Fetched['Status'] == 2) {echo "Selected";} ?>>Like New</option>
											<option value="3" <?php if ($item_Fetched['Status'] == 3) {echo "Selected";} ?>>Used</option>
											<option value="3" <?php if ($item_Fetched['Status'] == 4) {echo "Selected";} ?>>Old</option>
										</select>
									</div>
								</div>
								<!-- End Status Field -->
								<!-- Start Member Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Member :</label>
									<div class="col-sm-10 col-md-6">
										<select name="member">
											<option value="0">....</option>
											<?php
												$users = getAll("*", "users", "", "", "UserID",  "ASC","");
												foreach ($users as $user) {
													echo "<option value='" . $user['UserID'] . "'" ; 
														if ($item_Fetched['Member_ID'] == $user['UserID']) { echo "Selected"; } 
													echo ">";
														echo $user['Username'];
													echo "</option>";
												}
											?>
										</select>
									</div>
								</div>
								<!-- End Member Field -->
								<!-- Start Category Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Category :</label>
									<div class="col-sm-10 col-md-6">
										<select name="category">
											<option value="0">....</option>
											<?php
												$allCats = getAll("*", "categories", "WHERE Parent = 0", "", "ID",  "ASC","");
												foreach ($allCats as $cat) {
													echo "<option value='" . $cat['ID'] . "'" ; 
														if ($cat['ID'] == $item_Fetched['Cat_ID']) { echo "Selected"; } 
													echo ">";
														echo $cat['Name'];
													echo "</option>";

													$subCats = getAll("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID",  "ASC","");
													foreach ($subCats as $sub) {
														echo "<option value='" . $sub['ID'] . "'" ; 
															if ($sub['ID'] == $item_Fetched['Cat_ID']) { echo "Selected"; } 
														echo ">--- ";
															echo $sub['Name'];
														echo "</option>";
													}													
												}
											?>
										</select>
									</div>
								</div>
								<!-- End Category Field -->	
								<!-- End Avatar Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Item Img:</label>
									<div class="col-sm-10 col-md-6">
										<input type="file" name="Item_Img" class="form-control">
									</div>
								</div>
								<!-- End Avatar Field -->								
								<!-- Start Category Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Approval Status :</label>
									<div class="col-sm-10 col-md-6">
											<?php 
												$allApprovalStatus = getAll("Approval_Status", "items", "WHERE Item_ID = {$_GET['itemid']}", "", "Item_ID", "", ""); 
												foreach ($allApprovalStatus as $Itemstatus) {
													$status = $Itemstatus['Approval_Status'];
												}
											?>
										<select name="approval">
											<option value="0" <?php if($status == 0){ echo "Selected";} ?>  >Not approve</option>
											<option value="1" <?php if($status == 1){ echo "Selected";} ?> >approved</option>										
										</select>
									</div>
								</div>
								<!-- End Category Field -->	
								<!-- Start Tags Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Tags :</label>
									<div class="col-sm-10 col-md-6">
										<div data-tags-input-name="tags" id="tagBox"><?php $allTags = $item_Fetched['Tags']; 
												echo $allTags;
										?></div>
									</div>
								</div>
								<!-- End Tags Field -->																																												
								<!-- Start Submit Field -->
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Save Item" class="btn btn-primary btn-sm">
									</div>
								</div>
								<!-- End Submit Field -->
								<?php
								//Selecting The Users From DataBase Except The Admin
								$stmt = $db->prepare("	SELECT 
															comments.*,
															users.Username AS Member
														From
														 	comments 
														INNER JOIN 
															users
														ON 
															comments.user_id = users.UserID		
														WHERE item_id = ?								
														");
								//Execute the Prepared stmt
								$stmt->execute(array($itemid));
								//Fetching The Data
								$rows = $stmt->fetchAll();

								$count = $stmt->rowCount();
									if (!empty($rows)) {
									?>
										<h1 class="text-center">Mange [ <?php echo $item_Fetched['Name']; ?> ] Comments</h1>
										<div class="table-responsive">
											<table class="main-table text-center table table-bordered">
												<tr>
													<td>Comments</td>
													<td>User Name</td>
													<td>Comment date</td>
													<td>Control</td>
												</tr>
												<?php
													foreach ($rows as $row) {
														echo "<tr>";
															echo "<td>" . $row['comment'] . "</td>";
															echo "<td>" . $row['Member'] . "</td>";
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
									<?php } ?>
								</div>																				
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
		} elseif ($goTo == 'Update') {	//Update Item Page
				if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				//Upload Files

				if (!empty($_FILES['Item_Img'])) {

					$newAvatar = $_FILES['Item_Img'];

					$avatarName  =  $_FILES['Item_Img']['name'];
					$avatarType  =  $_FILES['Item_Img']['type'];
					$avatarTmp   =  $_FILES['Item_Img']['tmp_name'];
					$avatarError =  $_FILES['Item_Img']['error'];
					$avatarSize  =  $_FILES['Item_Img']['size'];

					$avatarAllowedExtensions = array("png","jpeg","jpg","gif");

					$avatarExtenstion = strtolower(end(explode(".", $avatarName)));						
				}	


				echo "<div class='container'>";
				echo "<h1 class='text-center'>Update Item</h1>";

				// Get The Variabales From The Form
				$itemid 	= $_POST['itemid'];
				$name 		= $_POST['name'];
				$disc 		= $_POST['discription'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$status 	= $_POST['status'];
				$member 	= $_POST['member'];
				$category 	= $_POST['category'];
				$approve 	= $_POST['approval'];
				$tags 	 	= implode(", ", $_POST['tags']);

				$check = checkItem('Item_ID', 'items', $itemid);

				// Erorr Handeling
				$formErorr = array(); 
				if (empty($name)) {
					$formErorr[] = 'Item Name Is Empty';
				}				
				if (empty($disc)) {
					$formErorr[] = 'Discription Is required';
				}
				if (empty($price)) {
					$formErorr[] = 'Price Is required And Must Be A Number';
				}
				if (empty($country)) {
					$formErorr[] = 'Country Is required';
				}
				if ($status == 0) {
					$formErorr[] = 'Status Must Be Greater Than 0 ';
				}	
				if ($member == 0) {
					$formErorr[] = 'member Must Be Greater Than 0 ';
				}	
				if ($category == 0) {
					$formErorr[] = 'category Must Be Greater Than 0 ';
				}

				if (!empty($avatarName) && !in_array($avatarExtenstion, $avatarAllowedExtensions)) {
					$formErorr[] = 'This Extenstion Is <strong>Not Allowed';
				}

				if (isset($avatarSize) && $avatarSize > 4192304) {
					$formErorr[] = 'Avatar Must Be Less Than <strong>4MB</strong>';
				}															
				foreach ($formErorr as $erorr) {
					$theMsg = '<div class="alert alert-danger">' . $erorr . '</div>';
					redirectTo($theMsg, 'back');					
				}	
	



				if (empty($formErorr)) {

					if (!empty($avatarName)) {
						// Avatar Upload
						$avatar = rand(0, 99999999999) . "_" . $avatarName;
						move_uploaded_file($avatarTmp, "uploads\Item_Img\\" . $avatar);
						copy('uploads\Item_Img\\' . $avatar, '..\uploads\Item_Img\\' . $avatar);						
					} else {
						$allAvatars = getAll("*", "items", "WHERE Item_ID = {$_POST['itemid']}", "", "Item_ID", "ASC", "");
						foreach ($allAvatars as $oldavatar) {
							$avatar = $oldavatar['Item_Img'];
						}
					}

					// Update The user Information
					$stmt = $db->prepare("  UPDATE 
												items 
											SET 
												Name = ?,
												Description	 = ?,
												Price = ?,
												Country_Made = ?,
												Status = ?,
												Member_ID = ?,
												Cat_ID = ?,
												Approval_Status = ?,
												Tags = ?,
												Item_Img = ?
											WHERE 
												Item_ID = ?");
					$stmt->execute(array($name, $disc , $price, $country, $status, $member, $category, $approve, $tags, $avatar,$itemid));

					// Success Mesage
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Updated</div>';

					echo "<div class='container'>";

					redirectTo($theMsg,"", 5);

					echo "</div>";	
				}						
			} else {
				echo "<h1 class='text-center'>Update Member</h1>" . '<br>';
				$theMsg = "<div class='alert alert-danger'>Sorry You can't Access This Page Dirctly</div>";

				redirectTo($theMsg);
			}	

		} elseif ($goTo == 'Delete') { 	//Delet Item page
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Delete Item</h1>";

			if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) ) {
				$itemid = intval($_GET['itemid']);

				//$stmt = $db->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1'); 

				//$stmt->execute(array($userid));

				//$count = $stmt->rowCount();

				$check = checkItem('Item_ID', 'items', $itemid);

				if ($check > 0) {

					$stmt = $db->prepare("DELETE FROM items WHERE Item_ID = :itemid"); 

					$stmt->bindParam(':itemid', $itemid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Deleted</div>';

					redirectTo($theMsg, 'back', 2);
				} else {
					$theMsg = "<div class='alert alert-danger'>there is No ID To Delete</div>";
					redirectTo($theMsg, 'back');					
				}
			}
			echo "</div>";		
		}  elseif ($goTo == 'Approve') { //approve Item Page
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Approve Member</h1>";

			if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) ) {
				$itemid = intval($_GET['itemid']);

				//$stmt = $db->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1'); 

				//$stmt->execute(array($itemid));

				//$count = $stmt->rowCount();

				$check = checkItem('Item_ID', 'items', $itemid);

				if ($check > 0) {

					$stmt = $db->prepare("UPDATE items SET Approval_Status = 1 WHERE Item_ID = ?"); 

					$stmt->execute(array($itemid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Approved</div>';

					redirectTo($theMsg, 'back');
				} else {
					$theMsg = "<div class='alert alert-danger'>there is No ID To Approve</div>";
					redirectTo($theMsg, 'back');					
				}
			}
			echo "</div>";

		}  else {
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
