<?php
	require 'connect.php';

	if ($_SESSION['access'] <= 2) {
  		header("Location: noaccess.php");
        exit;
	}

	if ($_POST ) {
		$ActivityListID = filter_input(INPUT_POST, 'ActivityListID', FILTER_VALIDATE_INT);
        $Notes = filter_input(INPUT_POST, 'Notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Date = preg_replace("([^0-9-])", "", $_POST['ActivityDate']);
        $Time = preg_replace("([^0-9:])", "", $_POST['ActivityTime']);
        $ActivityDate = implode(' ', array($Date, $Time)) ;
        if (strlen($ActivityListID) < 1 || strlen($ActivityListID) > 3 || $ActivityListID < 1  || strlen($Notes) < 1 || strlen($Notes) > 200 || ($ActivityDate < date("Y-m-d H:i:s")) ) {
        	header("Location: error.php?error=newevent");
        	exit;
        }
		$query1 = "INSERT INTO scheduledactivity (ActivityListID, ActivityDate, AvailableSpots, Notes) VALUES (:ActivityListID, :ActivityDate, :AvailableSpots, :Notes)";
		$statment1 = $db->prepare($query1);
		
		$query2 = "SELECT Participants FROM ActivityList WHERE ActivityListID = ${ActivityListID} LIMIT 1";

    	$statement2 = $db->prepare($query2);

    	$statement2->execute();
    	$Participants = $statement2->fetch();
    	$AvailableSpots = $Participants['Participants'];

		$statment1->bindValue(':ActivityListID', $ActivityListID);
		$statment1->bindValue(':ActivityDate', $ActivityDate);
		$statment1->bindValue(':Notes', $Notes);
		$statment1->bindValue(':AvailableSpots', $AvailableSpots);

		// This will be for adding to the activityuser table when users are made...
		
		if($statment1->execute()){
			header("Location: index.php");
			exit;
		}
	}
	$test = "test";
	$query = "SELECT * FROM ActivityList WHERE Active = 'y'
 	ORDER BY ActivityName";

    $statement = $db->prepare($query);

    $statement->execute();
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Schedule New Event</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<form method="post" action="newevent.php" id="newevent">
        	<label for="ActivityListID">Activity Name:</label>
        	<select id="ActivityListID" name="ActivityListID">
        		<?php while ($row = $statement->fetch()): ?>
        			<option value="<?= $row['ActivityListID'] ?>"><?= $row['ActivityName'] ?></option>
        		<?php endwhile ?>
        	</select>
        	<label for="ActivityDate">Date of Activity:</label>
        	<input id="ActivityDate" name="ActivityDate" type="date">
        	<label for="ActivityTime">Time of Activity:</label>
        	<input id="ActivityTime" name="ActivityTime" type="time">
        	<label for="Notes">Activity Notes:</label>
        	<textarea id="Notes" name="Notes"></textarea>
        	<input type="submit">
    	</form>
	</div>
</body>
</html>