<?php 

	ob_start(); //output buffring Start
	session_start();
	$pageTitle = "Dashboard";

	if (isset($_SESSION['username'])) {
		
		include 'init.php';
		$userLimit		= 5; //limit of latest Users
		$letestUsers 	= getLatest("*", "users", "UserID", $userLimit); // get the latest Items In db on Table users

		$itemLimit		= 5; //limit of latest items
		$letestItems 	= getLatest("*", "items", "Item_ID", $itemLimit); // get the latest Items In db on Table Items

		$commentLimit	= 5;		
?>
		<div class="container home-stat text-center">
			<h1>Dashboard</h1>
			<div class="row">
				<div class="col-md-3">
					<div class="stat st-member">
						Total Members
						<span><a href="member.php"><?php echo countItems('UserID', "users"); ?></a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-pending">
						Pending Members
						<span>
							<a href="member.php?page=Pending">
								<?php echo checkItem("RegStatus", "users", 0); ?>
							</a>
						</span>						
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-items">
						Total Items
						<span><a href="items.php"><?php echo countItems('Item_ID', "items"); ?></a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-comment">
						Total Comments
						<span><a href="comments.php"><?php echo countItems('c_id', "comments"); ?></a></span>
					</div>
				</div>												
			</div>
		</div>
		<div class="container latest">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"></i> Last <?php echo $userLimit; ?> Registed Users
						</div>
						<div class="panel-body">
							<ul class="list-unstyled letest-users">
								<?php
									foreach ($letestUsers as $user) {
										echo "<li>";
											echo $user['Username'];
											echo "<a href='member.php?goTo=Edit&userid=". $user['UserID'] ."'>";

												echo "<span class='btn btn-success pull-right'>";
													echo "<i class='fa fa-edit'></i> Edit";
												echo "</span>";
											echo "</a>";
											if ($user['RegStatus'] == 0) {
												echo "<a href='member.php?goTo=Activet&userid=" . $user['UserID'] ."'"." class='btn btn-info activet pull-right'><i class='fa fa-check'></i> Activet</a> ";
											}											
										echo "</li>";
									}
								?>								
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-tag"></i> Last <?php echo $itemLimit; ?> Items
						</div>
						<div class="panel-body">
							<ul class="list-unstyled letest-users">
								<?php
									foreach ($letestItems as $item) {
										echo "<li>";
											echo $item['Name'];
											echo "<a href='items.php?goTo=Edit&itemid=". $item['Item_ID'] ."'>";

												echo "<span class='btn btn-success pull-right'>";
													echo "<i class='fa fa-edit'></i> Edit";
												echo "</span>";
											echo "</a>";
											if ($item['Approval_Status'] == 0) {
												echo "<a href='items.php?goTo=Approve&itemid=" . $item['Item_ID'] ."'"." class='btn btn-info activet pull-right'><i class='fa fa-check'></i> Activet</a> ";
											}											
										echo "</li>";
									}
								?>								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container latest">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"></i> Last <?php  echo $commentLimit; ?> Comments
						</div>
						<div class="panel-body">
							<ul class="list-unstyled letest-users">							
								<?php
								//Selecting The Users From DataBase Except The Admin
								$stmt = $db->prepare("SELECT * From comments LIMIT $commentLimit");
								//Execute the Prepared stmt
								$stmt->execute();
								//Fetching The Data
								$comments = $stmt->fetchAll();

								$count = $stmt->rowCount();	
									foreach ($comments as $comment) {
										echo "<div class='comment-box'>";
											echo "<p> ". $comment['comment'];
											if ($comment['status'] == 0) {
												echo "<a href='comments.php?goTo=Approve&commentid=" . $comment['c_id'] ."'"." class='btn btn-sm btn-info activet pull-right'><i class='fa fa-check'></i> Approve</a> ";
											}
											echo "</p>";								
										echo "</div>";
									}
								?>								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>		
<?php

		include $templates . 'footer.php';
	} else {

		header('location: index.php');

		exit();
	}
	ob_end_flush(); //end output buffring