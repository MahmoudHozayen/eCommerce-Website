<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php getTitle(); ?></title>
		<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
		<link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
		<link rel="stylesheet" href="<?php echo $css; ?>tag-basic-style.css">		
		<link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
		<link rel="shortcut icon" href="ecommerce.png" />

	</head>
	<body>
	<div class="upper-bar">
		<div class="container">
			<?php if (isset($_SESSION['Username'])) { ?>
					<?php $userStauts = checkUserStauts($_SESSION['Username']);
						if ($userStauts == 1) {
							echo "Your Account Is Not Activeted Yet";
							echo "<span class='pull-right'><a href='logout.php'>Logout</a></span>";
						} else { 
					?>
						<?php
							$userInfo =  getAll("*", "users", "WHERE UserID = {$_SESSION['Uid']}", "", "UserID",'ASC', ""); 
							foreach ($userInfo as $info) {
								$Avatar = $info['Avatar'];
							}
						?>
						
						<img class="my-image img-thumbnail img-circle" src="<?php if(isset($info['Avatar']) && !empty($info['Avatar'])){ echo 'uploads\Avatars\\'. $Avatar;}else{ echo 'uploads\Avatars\img.png';} ?>" alt="" />
						<div class="btn-group my-info">
							<span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<?php echo $UserSession ?>
								<span class="caret"></span>
							</span>
							<ul class="dropdown-menu">
								<li><a href="profile.php">My Profile</a></li>
								<li><a href="newad.php">New Item</a></li>
								<li><a href="profile.php#ads">My Items</a></li>
								<li><a href="logout.php">Logout</a></li>
							</ul>
						</div>
					<?php }?>
			<?php } else{ ?>
				<span class="pull-right"><a href="login.php">LogIn/SighUp</a></span>
			<?php } ?>
		</div>
	</div>
	<nav class="navbar navbar-default navbar-inverse">     
	      <div class="container">
	        <!-- Brand and toggle get grouped for better mobile display -->
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="index.php">Homepage</a>
	        </div>

	        <!-- Collect the nav links, forms, and other content for toggling -->
	        <div class="collapse navbar-collapse" id="app-nav">
	          <ul class="nav navbar-nav navbar-right">
	            <?php
	            	$categories = $allCat = getAll("*", "categories", "WHERE Parent = 0", "", "ID", "", "");
	          		foreach ($categories as $cat) {
						//echo $cat['Name'] . "<br>";
	           			echo '<li>' . '<a href="categories.php?pageid='.$cat['ID'] .'&pagename='. str_replace(' ', '-', $cat['Name']) .'">' . $cat['Name'] .'</a>' . '</li>';
					}
	            ?>
	          </ul>
	          <!--<ul class="nav navbar-nav navbar-right">
	            <li class="dropdown">
	              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	                <?php /*getUserName();*/ echo " Foo Bar Baz "; ?><span class="caret"></span></a>
	              <ul class="dropdown-menu">
	                <li><a href="member.php?goTo=Edit&userid=<?php echo $_SESSION['ID'] ;?>"><?php echo lang('EDIT');?></a></li>
	                <li><a href="#"><?php echo lang('SETTINGS');?></a></li>
	                <li role="separator" class="divider"></li>
	                <li><a href="logout.php"><?php echo lang('OUT');?></a></li>
	              </ul>
	            </li>
	          </ul> -->
	        </div><!-- /.navbar-collapse -->   
	      </div><!-- /.container-fluid -->
	</nav>