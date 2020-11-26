<?php
	require 'connect.php';
	if ($_SESSION['access'] <= 3) {
  		header("Location: noaccess.php");
        exit;
	}

	if ($_POST ) {
		$ActivityName = filter_input(INPUT_POST, 'ActivityName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Participants = filter_input(INPUT_POST, 'Participants', FILTER_VALIDATE_INT);
        $ActivityInfo = filter_input(INPUT_POST, 'ActivityInfo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $CategoryID = filter_input(INPUT_POST, 'CategoryID', FILTER_VALIDATE_INT);
        if (strlen($ActivityName) < 1 || strlen($Participants) < 1 || strlen($ActivityName) > 50 || strlen($Participants) > 2 || $Participants < 1 || strlen($ActivityInfo) < 1 || strlen($ActivityInfo) > 200) {
        	header("Location: error.php?error=newactivity");
        	exit;
        }
		$query = "INSERT INTO activitylist (ActivityName, Participants, ActivityInfo, Category) VALUES (:ActivityName, :Participants, :ActivityInfo, :CategoryID)";
		$statment = $db->prepare($query);
			
		$statment->bindValue(':ActivityName', $ActivityName);
		$statment->bindValue(':Participants', $Participants);
		$statment->bindValue(':ActivityInfo', $ActivityInfo);
		$statment->bindValue(':CategoryID', $CategoryID);
		
		if($statment->execute()){
			header("Location: activitylist.php");
			exit;
		}
	}

	$catquery = "SELECT * FROM categories";
    $catstatement = $db->prepare($catquery);
    $catstatement->execute();

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
        	<label for="CategoryID">Category:</label>
        	<select id="CategoryID" name="CategoryID">
        		<?php while ($row = $catstatement->fetch() ): ?>
        			<option value="<?= $row['CategoryID'] ?>"><?= $row['CategoryName'] ?></option>
        		<?php endwhile ?>
        	</select>
        	<label for="ActivityInfo">Activity Info:</label>
        	<textarea id="ActivityInfo" name="ActivityInfo"></textarea>
        	<input type="submit">
    	</form>
	</div>
</body>
</html>