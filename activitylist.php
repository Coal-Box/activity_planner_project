<?php

	require 'connect.php';
	
	$query = "SELECT * FROM `activitylist` WHERE Active = 'y'";
    $statement = $db->prepare($query);

    $statement->execute();

    $i=0;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Activity List</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<h2 id="subtitle">All Available Activities</h2>
		<div class="container">
			<div class="row">
				<?php while ($row = $statement->fetch()): ?>
					<?php if($i % 3 == 0) :?>
						<div class="w-100"></div>
					<?php endif ?>
					<div class="col-sm">
						<h4><?= $row['ActivityName'] ?></h4>
						<h5><?= $row['Participants'] ?> Player Activity</h5>
						<h5><?= $row['ActivityInfo'] ?></h5>
					</div>
					<?php $i++ ?>
				<?php endwhile ?>
				<?php if($i % 3 !== 0) :?>
					<div class="col-sm"></div>
					<?php $i++ ?>
				<?php endif ?>
				<?php if($i % 3 !== 0) :?>
					<div class="col-sm"></div>
				<?php endif ?>
			</div>
		</div>
		<p><a href="newactivity.php">Add New</a></p>
	</div>
</body>
</html>