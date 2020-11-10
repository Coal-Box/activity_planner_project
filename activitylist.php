<?php

	require 'connect.php';
	
	$query = "SELECT * FROM `activitylist` WHERE Active = 'y'";
    $statement = $db->prepare($query);

    $statement->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Activity List</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<h2 id="subtitle">All Available Activities</h2>
		<?php while ($row = $statement->fetch()): ?>

		<?php endwhile ?>
	</div>
</body>
</html>