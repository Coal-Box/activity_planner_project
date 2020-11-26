<?php
	require 'connect.php';

	$query = "SELECT * FROM categories";
    $statement = $db->prepare($query);
    $statement->execute();
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Categories</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
		<ul>
			<?php while ($row = $statement->fetch()): ?>
				<li><a href="activitylist.php?id=<?= $row['CategoryID'] ?>"><h3><?= $row['CategoryName'] ?></h3></a></li>
				<?php if($_SESSION['access'] >= 3): ?>
					<p><a href="editcategory.php?id=<?= $row['CategoryID'] ?>">Edit^</a></p>
				<?php endif ?>
		<?php endwhile ?>
		</ul>
		<?php if($_SESSION['access'] >= 3): ?>
			<a href="newcategory.php">Add New</a>
		<?php endif ?>
	</div>
</body>
</html>