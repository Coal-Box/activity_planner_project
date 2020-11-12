<?php

	require 'connect.php';
	
	$query = "SELECT * FROM ActivityList INNER JOIN ScheduledActivity ON ActivityList.ActivityListID=ScheduledActivity.ActivityListID WHERE ActivityDate > CURRENT_TIMESTAMP
 ORDER BY ActivityDate LIMIT 5";
    $statement = $db->prepare($query);

    $statement->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<h2 id="subtitle">Upcoming Activites:</h2>
		<div id="upcoming-activities">
			<?php while ($row = $statement->fetch()): ?>
				<table>
					<tr>
						<td colspan="6"><h2><?= $row['ActivityName'] ?></h2></td>
					</tr>
					<tr>
						<td ><span><h3>Date:</h3></span></td>
						<td colspan="2"><?= $row['ActivityDate'] ?></td>
						<td colspan="2"><span><h3>Avalable Spots:</h3></span></td>
						<td><?= $row['AvailableSpots'] ?></td>
					</tr>
					<tr>
						<td colspan="2"><h3>Notes:</h4></td>
						<td colspan="4" class="notes"><p><?= $row['Notes'] ?></p></td>
					</tr>
				</table>
			<?php endwhile ?>
		</div>
	</div>
</body>

</html>