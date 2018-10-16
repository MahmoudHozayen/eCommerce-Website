<?php 
	ob_start(); //output buffring Start

	session_start();

	if (isset($_SESSION['Username'])) {
		$pageTitle = $_SESSION['Username'];
	} else {
		$pageTitle = "Profile";
	}

	include 'init.php';
	if (isset($_SESSION['Username'])) {

		$getUser = $db->prepare('SELECT * FROM users WHERE Username = ?');

		$getUser->execute(array($UserSession));

		$info = $getUser->fetch();

		$User_ID		 = $info['UserID'];	
?>
	<h1 class="text-center">My Profile</h1>

	<div class="information">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Information</div>
				<div class="panel-body">
					<ul class="list-unstyled">
						<li>
							<i class="fa fa-unlock-alt fa-fw"></i>
							<span>Login Name</span> : <?php echo $info['Username'] ?>
						</li>
						<li>
							<i class="fa fa-envelope-o fa-fw"></i>
							<span>Email</span> : <?php echo $info['Email'] ?>
						</li>
						<li>
							<i class="fa fa-user fa-fw"></i>
							<span>Full Name</span> : <?php echo $info['FullName'] ?>
						</li>
						<li>
							<i class="fa fa-calendar fa-fw"></i>
							<span>Registered Date</span> : <?php echo $info['Date'] ?>
						</li>
						<li>
							<i class="fa fa-tags fa-fw"></i>
							<span>Fav Category</span> :
						</li>						
					</ul>
					<a href="#" class="btn btn-default">Edit Information</a>					
				</div>
			</div>
		</div>
	</div>

	<div id="ads" class="ads block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Ads</div>
				<div class="panel-body">
					<?php
						$items	 	= getAll("*", "items", "Where Member_ID = {$User_ID}", "", "Item_ID", "", "");
						if (!empty($items)) {
							foreach ($items as $item) {
								echo '<div class="col-sm-6 col-md-3">';
									echo '<div class="thumbnail item-box">';
										echo '<span class="price-tag">$ ' . $item['Price'] . '</span>';
										if ($item['Approval_Status'] == 0) {echo '<span class="Approval-Status pull-right">' . "waiting for approval" . '</span>';}
										echo '<img class="img-responsive" src="';
											if (isset($item['Item_Img']) && !empty($item['Item_Img'])) {
												echo "uploads\Item_Img\\" . $item['Item_Img'];
											} else {
												echo "img.png";
											}
										echo '" alt="" />';
										echo '<div class="caption">';
											echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
											echo '<p>' . $item['Description'] . '</p>';
											echo '<div class="date">' . $item['Add_Date'] . '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
						} else {
							echo '<h1 class="text-center">' . 'You Haven\'t Posted Any Yet ' . '</h1>';
						}
					?>
				</div>
			</div>
		</div>
	</div>

	<div class="last-com">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Latest Comments</div>
				<div class="panel-body">
					<?php
						$stmt = $db->prepare("	SELECT 
													comments.*,
													items.Name AS itemName
												From
												 	comments 
												INNER JOIN 
													items
												ON 
													comments.item_id = items.Item_ID
												WHERE user_id = ?								
												");
						//Execute the Prepared stmt
						$stmt->execute(array($info['UserID']));
						//Fetching The Data
						$comments = $stmt->fetchAll();
						if (!empty($comments)) {
							foreach ($comments as $comment) {?>
								<div class="col-md-offset-3 col-md-9 comment-box">
									<span class="item-commented">
										<a href="items.php?itemid=<?php echo $comment['item_id'] ?>"><?php echo $comment['itemName'] ?></a>
									</span>											
									<div class="comment">
										<?php echo $comment['comment'] ?>
										<span class="comment-date">
											<?php echo $comment['comment_date'] ?>
										</span>										
									</div>
								</div>
						<?php	}
						}else {
								echo "<p>" . "Ther Is No Comments To Show" . "</p>";
						}
					?>
				</div>
			</div>
		</div>
	</div>

<?php 
	}else {
		header('Location: Login.php');
		exit();
	}

	include $templates . 'footer.php'; 
	ob_end_flush(); //end output buffring
?>