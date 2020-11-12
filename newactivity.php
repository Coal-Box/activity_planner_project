<?php
	require 'connect.php';
	require 'authenticate.php';
	if ($_POST ) {
		$ActivityName = filter_input(INPUT_POST, 'ActivityName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Participants = filter_input(INPUT_POST, 'Participants', FILTER_VALIDATE_INT);
        $ActivityInfo = filter_input(INPUT_POST, 'ActivityInfo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strlen($ActivityName) < 1 || strlen($Participants) < 1 || strlen($ActivityName) > 50 || strlen($Participants) > 2 || $Participants < 1 || strlen($ActivityInfo) < 1 || strlen($ActivityInfo) > 200 ) {
        	header("Location: error.php?error=newactivity");
        	exit;
        }
		$query = "INSERT INTO activitylist (ActivityName, Participants, ActivityInfo) VALUES (:ActivityName, :Participants, :ActivityInfo)";
		$statment = $db->prepare($query);
			
		$statment->bindValue(':ActivityName', $ActivityName);
		$statment->bindValue(':Participants', $Participants);
		$statment->bindValue(':ActivityInfo', $ActivityInfo);
		
		if($statment->execute()){
			header("Location: activitylist.php");
			exit;
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>New Activity</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<form method="post" action="newactivity.php" id="newactivity">
        	<label for="ActivityName">Activity Name:</label>
        	<input id="ActivityName" name="ActivityName">
        	<label for="Participants">Participants:</label>
        	<input id="Participants" name="Participants" type="number">
        	<label for="ActivityInfo">Activity Info:</label>
        	<textarea id="ActivityInfo" name="ActivityInfo"></textarea>
        	<input type="submit">
    	</form>
	</div>
</body>
</html>