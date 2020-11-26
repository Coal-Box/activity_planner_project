<?php
	require 'connect.php';

	if ($_GET) {
		$ScheduledActivityID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
		if (!filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
			header("Location: scheduledactivities.php");
		}
		$query = "SELECT * FROM ActivityList INNER JOIN ScheduledActivity ON ActivityList.ActivityListID=ScheduledActivity.ActivityListID WHERE ScheduledActivityID = :ScheduledActivityID LIMIT 1";
    	$statement = $db->prepare($query);
    	$statement->bindValue(':ScheduledActivityID', $ScheduledActivityID);
    	$statement->execute();

    	$row = $statement->fetch();
	}else{
		header("Location: scheduledactivities.php");
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Event</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
		<h2><?= $row['ActivityName'] ?></h2>
<!-- 	When scheduleduser is set up add list of people here -->
		<h3>The participants are:</h3>
		<h3>Date of Event: <?= $row['ActivityDate'] ?></h3>
		<h3>There are <?= $row['AvailableSpots'] ?> spots left</h3>
		<p>Notes regarding event: <?= $row['Notes'] ?></p>
		<h3><a href="#">Join</a></h3>
		<?php if($_SESSION['access'] >= 3): ?>
			<h3><a href="editevent.php?id=<?= $row['ScheduledActivityID'] ?>">Edit</a></h3>
		<?php endif ?>
	</div>
</body>
</html>