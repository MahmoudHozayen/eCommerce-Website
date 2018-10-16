<?php
	ob_start();
	session_start();
	$pageTitle = 'Create New Item';
	include 'init.php';
	if (isset($_SESSION['Username'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$formError = array();

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

			$name 			= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$discription 	= filter_var($_POST['discription'], FILTER_SANITIZE_STRING);
			$price 			= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$country 		= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$status 		= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$category 		= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
			$tags 			= filter_var(implode(", ", $_POST['tags']), FILTER_SANITIZE_STRING);
			// Erorr Handeling
			if (empty($name) || empty($discription) || empty($price) || empty($country) || empty($category) || empty($status)) {
				$formError[] = "Empty Input Error" ;
			}
			if (strlen($name) < 2 || strlen($discription) < 10 ) {
				$formError[] = "Short Input Error" ;
			}
			if (!is_numeric($price) || !is_numeric($status)) {
				$formError[] = "Not A number Input Error" ;
			}
			if (!empty($avatarName) && !in_array($avatarExtenstion, $avatarAllowedExtensions)) {
				$formError[] = 'This Extenstion Is <strong>Not Allowed';
			}
			if (isset($avatarSize) &&  $avatarSize > 4192304) {
				$formError[] = 'Avatar Must Be Less Than <strong>4MB</strong>';
			}
			if (empty($formError)) {

					if (!empty($avatarName)) {
						$avatar = rand(0, 99999999999) . "_" . $avatarName;

						move_uploaded_file($avatarTmp, "uploads\Item_Img\\" . $avatar);
						//copy the img from admin to front end
						copy('uploads\Item_Img\\' . $avatar, 'admin\uploads\Item_Img\\' . $avatar);
					} else {
						$avatar = "";
					}	

					// Add The Item Information
					$stmt = $db->prepare("	INSERT INTO 
													items (Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID, Tags, Item_Img)
											VALUES 
													(:name, :disc, :price, :country, :status, now(), :member, :category, :tags, :itmeimg)");
					$stmt->execute(array(
									':name' 	=> $name ,
									':disc' 	=> $discription, 
									':price' 	=> $price, //$ Sign is Added After check If the Price is numeric
									':country' 	=> $country,
									':status' 	=> $status,
									':member' 	=> $_SESSION['Uid'],
									':category' => $category,
									':tags'		=> $category,
									':itmeimg' => $avatar));
					// Success Mesage
					$SuccessMesage = "<div class='alert alert-success'> 1 item Inserted</div>";

				}	
		}
?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $pageTitle ?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8 main-form">
						<form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
							<!-- Start Item Name Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Name :</label>
								<div class="col-sm-10 col-md-9">
									<input 
											type="text" 
											name="name" 
											required="required" 
											class="form-control live" 
											placeholder="Add Item Name"
											data-class=".live-title">
								</div>
							</div>
							<!-- End Item Name Field -->
							<!-- Start Discription Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Discription :</label>
								<div class="col-sm-10 col-md-9">
									<input 
										type="text" 
										name="discription"
										required="required" 										  
										class="form-control live" 
										placeholder="Discription Of The Item "
										data-class=".live-desc">
								</div>
							</div>
							<!-- End Discription Field -->
							<!-- Start Price Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Price :</label>
								<div class="col-sm-10 col-md-9">
									<input 
										type="text" 
										name="price"
										required="required" 										  
										class="form-control live" 
										placeholder="Price Of The Item $"
										data-class=".live-price">
								</div>
							</div>
							<!-- End Price Field -->
							<!-- Start Country Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Country :</label>
								<div class="col-sm-10 col-md-9">
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
								<div class="col-sm-10 col-md-9">
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
							<!-- Start Category Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Category :</label>
								<div class="col-sm-10 col-md-9">
									<select name="category">
										<option value="0">....</option>										
										<?php
											$allCat = getAll("*", "categories", "", "", "ID", "", "");
											foreach ($allCat as $cat) {
												echo "<option value=".$cat['ID'].">".$cat['Name']."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Category Field -->	
							<!-- Start Tags Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Tags :</label>
								<div class="col-sm-10 col-md-9">
									<div data-tags-input-name="tags" id="tagBox"></div>
								</div>
							</div>
							<!-- End Tags Field -->							
							<!-- End Avatar Field -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Item Img :</label>
								<div class="col-sm-10 col-md-9">
									<input type="file" name="Item_Img" class="form-control">
								</div>
							</div>
							<!-- End Avatar Field -->							
							<?php
								if (!empty($formError)) {
									foreach ($formError as $error) {
										echo "<div class='alert alert-danger'>" . $error . "</div>";
									}
								}else {
									if (isset($SuccessMesage)) {
										echo $SuccessMesage;
									}
								}
							?>																										
							<!-- Start Submit Field -->
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Add Item" class="btn btn-primary btn-sm">
								</div>
							</div>
							<!-- End Submit Field -->										
						</form>
					</div>
					<div class="col-md-4">
						<div class="thumbnail item-box live-preview">
							<span class="price-tag">
								$<span class="live-price">0</span>
							</span>
							<img class="img-responsive" src="img.png" alt="" />
							<div class="caption">
								<h3 class="live-title">Title</h3>
								<p class="live-desc">Description</p>
								<div class="date"><?php echo date('Y-m-d'); ?></div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $templates . 'footer.php'; 
	ob_end_flush();
?>

