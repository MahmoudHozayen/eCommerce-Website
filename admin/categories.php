<?php 
	ob_start(); //output buffring Start
	session_start();
	$pageTitle = 'Categories';
	if (isset($_SESSION['username'])) {
		include 'init.php';

		$goTo = '';
		if (isset($_GET['goTo'])) {

			$goTo = $_GET['goTo'];
		} else {
			
			$goTo = 'Mange';
		}

		if ($goTo == 'Mange') { 		// Manage categories Page

			$sort = 'ASC';

			$sor_array = array("ASC", "DESC");

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sor_array)) {

				$sort = $_GET['sort'];
			}

			$categories = getAll("*", "categories", "WHERE Parent = 0", "", "Ordering", "$sort","");
			
			?>

			<h1 class="text-center">Mange Categorie Page</h1>
			<div class="container categories">
				<div class="panel panel-default">
					<div class="panel-heading">
						Mange Categorie :
						<div class="option pull-right ">
							<i class="fa fa-sort"></i> Ordering : [
							<a href="?sort=DESC" class="<?php if ($sort == 'DESC') {echo 'active';} ?>">DESC</a> |
							<a href="?sort=ASC"  class="<?php if ($sort == 'ASC') {echo 'active';} ?>">ASC</a> ] 
							<i class="fa fa-eye"></i> View Option : [
							<span class="active" data-view="full">Full</span> |
							<span data-view="clasic">Clasic</span> ]
						</div>
					</div>
					<div class="panel-body">
						<?php
							if (! empty($categories)) {
								foreach ($categories as $cat) {
									echo "<div class='cat'>";

										echo "<div class='hidden-buttons'>";
											echo "<a href='categories.php?goTo=Edit&catid=". $cat['ID'] ."'class='btn btn-primary'><i class='fa fa-edit'></i> Edit</a>";

											echo "<a href='categories.php?goTo=Delete&catid=". $cat['ID'] ."'class='confirm btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
										echo "</div>";

									 	echo "<h3>" . $cat['Name'] . "</h3>";

									 	echo "<div class='full-view'>";
										 	echo "<p>";
										 		 if ($cat['Description'] == ""){
										 		 		echo "There is No Discription";
										 		 	} else { 
										 		 		echo $cat['Description'];
										 		 	}
										 	echo "</p>";
									 		if ( $cat['Visibility'] == 1) {echo "<span class='visibility cat-span'><i class='fa fa-eye'></i> " . "Hidden" . "</span>";}
									 		if ( $cat['Allow_Comment'] == 1) {echo "<span class='commenting cat-span'><i class='fa fa-close'></i> " . "Comment disable" . "</span>";}
									 		if ( $cat['Allow_Ads'] == 1) {echo "<span class='advertises cat-span'><i class='fa fa-close'></i> " . "Ads disable" . "</span>";}
										 	
										 	// Sub categories 
											$subCat		= getAll("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID", "","");
											if (! empty($subCat)) {
												echo "<hr>";
												echo "<h4 class='child-head'>" . "Child Categories : " . "</h4>";
												echo "<ul class='list-unstyled'>";
													foreach ($subCat as $sub) {
														echo "<li class='child-cat'>";
															echo "<a href='categories.php?goTo=Edit&catid=". $sub['ID'] ."'>" . $sub['Name'] . "</a>";
															echo "<a href='categories.php?goTo=Delete&catid=". $sub['ID'] ."'class='confirm sub-delete'> Delete</a>";
														echo "</li>";
													}
												echo "</ul>";
											}
									 	echo "</div>";								 	
									echo "</div>";
									echo "<hr>";								 	

								}								
							}
						?>
					</div>
				</div>
				<a href="categories.php?goTo=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add Categorie</a>
			</div>

			<?php

		} elseif ($goTo == 'Add') { 	//Add Categorie Page ?>
					<h1 class="text-center">Add New Categorie</h1>
					<div class="container">
						<form class="form-horizontal" method="POST" action="?goTo=Insert">
							<!-- Start Categorie Name Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Name :</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="name" required="required" class="form-control" placeholder="Add Categorie Name">
								</div>
							</div>
							<!-- End Categorie Name Field -->
							<!-- Start Password Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Discription :</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="discription"  class="form-control" placeholder="Discription Of The Categorie ">
								</div>
							</div>
							<!-- End Password Field -->	
							<!-- Start E-mail Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Ordering :</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="order" class="form-control" placeholder="Order Of The Categorie">
								</div>
							</div>
							<!-- End E-mail Field -->
							<!-- Start Parent Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Parent :</label>
								<div class="col-sm-10 col-md-6">
									<select name="parent">
										<option value="0">None</option>
										<?php
											$parentCat =  getAll("*", "categories", "WHERE Parent = 0", "", "ID", 'ASC',"");
											if (!empty($parentCat)) {
												foreach ($parentCat as $Cat) {
													echo "<option value='". $Cat['ID'] ."''>" . $Cat['Name'] . "</option>";
												}
											}
										?>
									</select>	
								</div>
							</div>
							<!-- End Parent Field -->							
							<!-- Start visibility Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Visibile :</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="vis-yes" type="radio" name="visibility" value="0" checked>
										<label for="vis-yes">Yes</label>
									</div>
								</div>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="vis-no" type="radio" name="visibility" value="1">
										<label for="vis-no">No</label>
									</div>
								</div>								
							</div>
							<!-- End visibility Field -->	
							<!-- Start commenting Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Allow Commenting :</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="com-yes" type="radio" name="commenting" value="0" checked>
										<label for="com-yes">Yes</label>										
									</div>
								</div>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="com-no" type="radio" name="commenting" value="1">
										<label for="com-no">No</label>
									</div>	
								</div>								
							</div>
							<!-- End commenting Field -->	
							<!-- Start commenting Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Allow Ads :</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="ads-yes" type="radio" name="ads" value="0" checked>
										<label for="ads-yes">Yes</label>
									</div>	
								</div>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="ads-no" type="radio" name="ads" value="1">
										<label for="ads-no">No</label>
									</div>	
								</div>								
							</div>
							<!-- End commenting Field -->							

							<!-- Start Submit Field -->
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Add Categorie" class="btn btn-primary">
								</div>
							</div>
							<!-- End Submit Field -->										
						</form>
					</div>			
<?php	} elseif ($goTo == 'Insert') { 	//insert Categorie Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<div class='container'>";
					echo "<h1 class='text-center'>Insert Categorie Page</h1>";

					// Get The Variabales From The Form
					$name 		= $_POST['name'];
					$disc 		= $_POST['discription'];
					$parent 	= $_POST['parent'];
					$order 		= $_POST['order'];
					$visibile 	= $_POST['visibility'];
					$comment 	= $_POST['commenting'];
					$ads 		= $_POST['ads'];

					$check = checkItem('Name', 'categories', $name);

					if ($check == 1){
						echo "<div class='container'>";
						
						$theMsg = "<div class='alert alert-danger'>Sorry Categorie Name is Exists</div>";
						
						redirectTo($theMsg, 'back');

						echo "</div>";
					}

					if (!empty($name)) {
						// Add The Categorie Information
						$stmt = $db->prepare("	INSERT INTO 
														categories (Name, Description,Parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
												VALUES 
														(:name, :disc, :parent, :order, :visibile, :comment, :ads)");
						$stmt->execute(array(
										':name' 	=> $name ,
										':disc' 	=> $disc, 
										':parent' 	=> $parent, 
										':order' 	=> $order,
										':visibile' => $visibile,
										':comment' 	=> $comment,
										':ads' 		=> $ads));
						// Success Mesage
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Inserted</div>";

						redirectTo($theMsg, 'back');
					} else {
						echo "<div class='container'>";
						
						$theMsg = "<div class='alert alert-danger'>Sorry Categorie Name can't be Empty</div>";
						
						redirectTo($theMsg, 'back');

						echo "</div>";
					}
				echo "</div>";		
			} else {
				echo "<div class='container'>";
				
				$theMsg = "<div class='alert alert-danger'>Sorry You can't Access This Page Dirctly</div>";
				
				redirectTo($theMsg, "back");

				echo "</div>";
			}	
		} elseif ($goTo == 'Edit') { 	//Edit categoier Page
				if (isset($_GET['catid']) && is_numeric($_GET['catid']) ) {

					$catid = intval($_GET['catid']);

					$stmt = $db->prepare("SELECT * FROM categories WHERE ID = ?"); 

					//GroupID = 1 Mean He Is Admin //GroupID = 0 Mean He Is User

					$stmt->execute(array($catid));

					$row = $stmt->fetch(); //fetch the data from database

					$count = $stmt->rowCount();

					$check = checkItem('ID', 'categories', $catid);

					if ($count > 0 && $check > 0) { ?>

						<h1 class="text-center">Edit Categorie</h1>
						<div class="container">
							<form class="form-horizontal" method="POST" action="?goTo=Update">
								<input type="hidden" name="catid" value="<?php echo $catid; ?>">

								<!-- Start Categorie Name Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Name :</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="name" required="required"  class="form-control" placeholder="Add Categorie Name" value="<?php echo $row['Name']; ?>">
									</div>
								</div>
								<!-- End Categorie Name Field -->
								<!-- Start Password Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Discription :</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="discription"  class="form-control" placeholder="Discription Of The Categorie " value="<?php echo $row['Description']; ?>">
									</div>
								</div>
								<!-- End Password Field -->	
								<!-- Start Ordering Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Ordering :</label>
									<div class="col-sm-10 col-md-6">
										<input type="text" name="order" class="form-control" placeholder="Order Of The Categorie" value="<?php echo $row['Ordering']; ?>">
									</div>
								</div>
								<!-- End Ordering Field -->
								<!-- Start Parent Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Parent :</label>
									<div class="col-sm-10 col-md-6">
										<select name="parent">
											<option value="0">None</option>
											<?php
												$parentCat =  getAll("*", "categories", "WHERE Parent = 0", "", "ID", 'ASC',"");
												if (!empty($parentCat)) {
													foreach ($parentCat as $Cat) {
														echo "<option value='". $Cat['ID'] ."'";
														if ($row['Parent'] == $Cat['ID']) {
															echo "selected";
														}
														echo">" . $Cat['Name'] . "</option>";
													}
												}
											?>
										</select>	
									</div>
								</div>
								<!-- End Parent Field -->								
								<!-- Start visibility Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Visibile :</label>
									<div class="col-sm-10 col-md-6">
										<div>
											<input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($row['Visibility'] == 0) { echo "checked";} ?>>
											<label for="vis-yes">Yes</label>
										</div>
									</div>
									<div class="col-sm-10 col-md-6">
										<div>
											<input id="vis-no" type="radio" name="visibility" value="1" <?php if ($row['Visibility'] == 1) { echo "checked";} ?>>
											<label for="vis-no">No</label>
										</div>
									</div>								
								</div>
								<!-- End visibility Field -->	
								<!-- Start commenting Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Allow Commenting :</label>
									<div class="col-sm-10 col-md-6">
										<div>
											<input id="com-yes" type="radio" name="commenting" value="0"<?php if ($row['Allow_Comment'] == 0) { echo "checked";} ?>>
											<label for="com-yes">Yes</label>										
										</div>
									</div>
									<div class="col-sm-10 col-md-6">
										<div>
											<input id="com-no" type="radio" name="commenting" value="1" <?php if ($row['Allow_Comment'] == 1) { echo "checked";} ?>>
											<label for="com-no">No</label>
										</div>	
									</div>								
								</div>
								<!-- End commenting Field -->	
								<!-- Start Allow_Ads Field -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Allow Ads :</label>
									<div class="col-sm-10 col-md-6">
										<div>
											<input id="ads-yes" type="radio" name="ads" value="0" <?php if ($row['Allow_Ads'] == 0) { echo "checked";} ?>>
											<label for="ads-yes">Yes</label>
										</div>	
									</div>
									<div class="col-sm-10 col-md-6">
										<div>
											<input id="ads-no" type="radio" name="ads" value="1" <?php if ($row['Allow_Ads'] == 1) { echo "checked";} ?>>
											<label for="ads-no">No</label>
										</div>	
									</div>								
								</div>
								<!-- End Allow_Ads Field -->							

								<!-- Start Submit Field -->
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Update Categorie" class="btn btn-primary">
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
		} elseif ($goTo == 'Update') {	//Update categoier Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<div class='container'>";
				echo "<h1 class='text-center'>Update Categorie</h1>";

				// Get The Variabales From The Form
				$id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['discription'];
				$order 		= $_POST['order'];
				$parent 	= $_POST['parent'];
				$visibile 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$advertises = $_POST['ads'];

				$check = checkItem('Name', 'categories', $name);

				if (!empty($name)) {
					// Update The catergory Information
					$stmt = $db->prepare("	UPDATE 
													categories 
											SET 	Name = ?,
													Description	 = ?,
													Ordering = ?, 
													Parent = ?, 
													Visibility = ?, 
													Allow_Comment = ?,
													Allow_Ads = ? 
											WHERE 
													ID = ?");
					$stmt->execute(array($name, $desc , $order, $parent, $visibile, $comment, $advertises, $id));

					// Success Mesage
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Updated</div>';

					echo "<div class='container'>";

					redirectTo($theMsg, 'back');

					echo "</div>";					
				} else {
					$theMsg = "<div class='alert alert-danger'>Sorry You can't Leave Name Field Empty</div>";

					redirectTo($theMsg, 'back');					
				}				
			} else {
				echo "<h1 class='text-center'>Update Categorie</h1>" . '<br>';
				$theMsg = "<div class='alert alert-danger'>Sorry You can't Access This Page Dirctly</div>";

				redirectTo($theMsg);
			}			

		} elseif ($goTo == 'Delete') { 	//Delet categoier page
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Delete Categorie</h1>";

			if (isset($_GET['catid']) && is_numeric($_GET['catid']) ) {
				$catid = intval($_GET['catid']);

				$check = checkItem('ID', 'categories', $catid);

				if ($check > 0) {

					$stmt = $db->prepare("DELETE FROM categories WHERE ID = :catid"); 

					$stmt->bindParam(':catid', $catid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Deleted</div>';

					redirectTo($theMsg, 'back', 2);
				} else {
					$theMsg = "<div class='alert alert-danger'>there is No ID To Delete</div>";
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