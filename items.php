<?php 
	ob_start(); //output buffring Start

	session_start();

	$pageTitle = "Full Item Details ";

	include 'init.php';

	if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) ) {

		$itemid = intval($_GET['itemid']);

		$stmt = $db->prepare("SELECT 
								items.*, 
								categories.Name AS category_name, 
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
								users.UserID = items.Member_ID 
							WHERE 
								Item_ID = ?
							AND 
								Approval_Status = 1"); 
		$stmt->execute(array($itemid));

		$count = $stmt->rowCount();
	}

	if ($count > 0) {
		$item_Fetched = $stmt->fetch(); //fetch the data from database
	?>
		<h1 class="text-center"><?php echo $item_Fetched['Name'] ?></h1>
		<div class="container">
		<div class="row">
			<div class="col-md-3">
						<?php
							$itemInfo =  getAll("*", "items", "WHERE Item_ID = {$item_Fetched['Item_ID']}", "", "Item_ID",'ASC', ""); 
							foreach ($itemInfo as $info) {
								$itemimg = $info['Item_Img'];
							}
						?>				
				<img class="img-responsive img-thumbnail center-block" src="<?php if(isset($info['Item_Img']) && !empty($info['Item_Img'])){ echo 'uploads\Item_Img\\'. $itemimg;}else{ echo 'uploads\Item_Img\img.png';} ?>"  alt="" />
			</div>
			<div class="col-md-9 item-info">
				<h2>Name :<?php echo $item_Fetched['Name']; ?></h2>
				<p>Description : <?php echo $item_Fetched['Description'] ?></p> 
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Added Date : </span> <?php echo $item_Fetched['Add_Date'] ?>
					</li>
					<li>
						<i class="fa fa-money fa-fw"></i>
						<span>Price : </span><?php echo $item_Fetched['Price'] ?>
					</li>
					<li>
						<i class="fa fa-eye fa-fw"></i>
						<span>Made In : </span>  <?php echo $item_Fetched['Country_Made'] ?>
					</li>
					<li>
						<i class="fa fa-tag fa-fw"></i>
						<span>Category : </span><a href="categories.php?pageid=<?php echo $item_Fetched['Cat_ID'] ?>"> <?php echo $item_Fetched['category_name'] ?></a>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Added By : </span><a href="#"> <?php echo $item_Fetched['Username'] ?></a>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Tag : </span>
						<?php
							$tags = explode(",", $item_Fetched['Tags']);
							foreach ($tags as $tag) {
								$tag = str_replace(" ", "", $tag);

								echo "<a href='tags.php?tag-name=". strtolower($tag) ."'><span class='tag-name'><i class='fa fa-close pull-left'></i>" .  strtoupper($tag) . "</span></a>";
							}
						?>
					</li>								
				</ul>
			</div>
		</div>
		<hr class="custom-hr">
		<!-- Add Comment Section -->
		<?php if (isset($_SESSION['Username'])) { ?>
			<div class="row">
				<div class="col-md-offset-3">
					<div class="add-comment">
						<h3>Add Comment</h3>
						<form action="<?php echo $_SERVER['PHP_SELF'] . "?itemid=" . $item_Fetched['Item_ID'];?>" method="POST">
							<textarea name="comment"></textarea>
							<input class="btn btn-primary" type="submit" value="Add Comment">
						</form>
						<?php
							if ($_SERVER['REQUEST_METHOD'] == 'POST') {
								$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
								$itemID 	= $item_Fetched['Item_ID'];
								$userID 	= $_SESSION['Uid'];

								if (! empty($comment)) {
									$stmt = $db->prepare("
											INSERT INTO 
												comments(comment, status, comment_date, item_id, user_id)
											VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)
											");
									$stmt->execute(array(
										"zcomment" => $comment,
										"zitemid" => $itemID,
										"zuserid" => $userID));
									if ($stmt) {
										echo "<div class='alert alert-success'>Comment Added</div>";
									} else {
										echo "<br><div class='alert alert-danger'>Sorry Comment Cannot be added</div>";
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		<?php }else { 
			echo "<a href='login.php'>Login</a> Or <a href='login.php'>SignUp</a> To Add Comment";
		}?>
		<!-- Add Comment Section -->		
		<hr class="custom-hr">
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
										WHERE
											item_id = ?
										AND 
											status = 1										
										ORDER BY 
											comments.c_id
										ASC									
										");
				//Execute the Prepared stmt
				$stmt->execute(array($item_Fetched['Item_ID']));
				//Fetching The Data
				$comments = $stmt->fetchAll();

				if (empty($comments)) {
					echo 'There\' No Comments To Show<br><hr class = "custom-hr">';
				}
			?>
			<?php foreach ($comments as $comment) { ?>
					<div class="row comment-box">
						<div class="col-md-3">
							<div class="comment-member">
								<div class="member-img"><img class="img-responsive" src="img.png"></div>
								<span class="member-name"><?php echo $comment['Member'] ?></span>
							</div>			
						</div>
						<div class="col-md-9">
							<div class="comment">
								<?php echo $comment['comment'] ?>
								<span class="comment-date">
									<?php echo $comment['comment_date'] ?>
								</span>										
							</div>
						</div>
					</div>
					<hr class="custom-hr comment-custom-hr">
			<?php } ?>		
		</div>
	<?php 
	} else {
		echo "<div class='container'>";

			$theMsg = '<div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div>';

			redirectTo($theMsg, "Back", $seconds = 1);	

		echo "</div>";
	}
	?>
<?php	
	include $templates . 'footer.php'; 
	ob_end_flush(); //end output buffring
?>