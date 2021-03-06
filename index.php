<?php 
	ob_start();
	session_start();
	$pageTitle = "Homepage";
	include 'init.php'; //pageid=21&pagename=Computers
?>
	<div class="container">
		<span class="toggel-cat"> All categories <i class="fa fa-arrow-down"></i></i></span>
		<?php $allPaerantCats = getAll("*", "categories", "Where Parent = 0", "", "ID", "ASC", ""); ?>
		<div class="sub-cats">
			<?php foreach ($allPaerantCats as $cat) { ?>
				<div class="parent-cat col-sm-6 col-md-3">
					<h4 class="parent-cat-name"><?php echo $cat['Name']; ?></h4>
					<?php $allSubCats = getAll("*", "categories", "Where Parent = {$cat['ID']}", "", "ID", "ASC", ""); ?>
					<?php foreach ($allSubCats as $sub) { ?>
							<ul class="list-unstyled">
								<?php $cleanSubName = str_replace(" ", "-", $sub['Name']); ?>
								<li>- <a href="categories.php?pageid=<?php echo $sub['ID']. '&pagename=' . $cleanSubName ; ?>"><?php echo $sub['Name']; ?></a></li>
							</ul>				
					<?php } ?>
				</div>
			<?php } ?>								
		</div>
		<h1 class="text-center"><?php echo $pageTitle; ?></h1>
		<?php
			$items		 = getAll('*', 'items', 'Where Approval_Status = 1', '', 'Item_ID', 'DESC', '');

			if (!empty($items)) {
				foreach ($items as $item) {
					echo '<div class="col-sm-6 col-md-3">';
						echo '<div class="thumbnail item-box">';
								echo '<span class="price-tag">'. "$" . $item['Price'] . '</span>';
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
				echo '<h1 class="text-center">' . 'Sorry This Page Is Empty Now' .'</h1>';
			}
		?>
	</div>
<?php
	include $templates . 'footer.php';
	ob_end_flush();
?>