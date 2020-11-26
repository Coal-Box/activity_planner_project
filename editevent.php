<?php
	require 'connect.php';
    if ($_POST && isset($_POST['ActivityListID']) && isset($_POST['ActivityDate']) && isset($_POST['ActivityTime']) && isset($_POST['id']) && isset($_POST['Notes']) && isset($_POST['update']) ) {

        $ActivityListID = filter_input(INPUT_POST, 'ActivityListID', FILTER_VALIDATE_INT);
        $Notes = filter_input(INPUT_POST, 'Notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Date = preg_replace("([^0-9-])", "", $_POST['ActivityDate']);
        $Time = preg_replace("([^0-9:])", "", $_POST['ActivityTime']);
        $ActivityDate = implode(' ', array($Date, $Time)) ;
        $ScheduledActivityID = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (strlen($ActivityListID) < 1 || strlen($ActivityListID) > 3  || strlen($Notes) < 1 || strlen($Notes) > 200 || ($ActivityDate < date("Y-m-d H:i:s")) ) {
                header("Location: error.php?error=editevent?id={$ScheduledActivityID}");
                exit;
            }

        $query = "UPDATE ScheduledActivity SET ActivityListID = :ActivityListID, ActivityDate = :ActivityDate, Notes = :Notes WHERE ScheduledActivityID = :ScheduledActivityID";
        $statement = $db->prepare($query);
        $statement->bindValue(':ActivityListID', $ActivityListID);
        $statement->bindValue(':ActivityDate', $ActivityDate);
        $statement->bindValue(':Notes', $Notes);
        $statement->bindValue(':ScheduledActivityID', $ScheduledActivityID, PDO::PARAM_INT);
        $statement->execute();

        header("Location: editevent.php?id={$ScheduledActivityID}");
        exit;
    } else if ($_POST && isset($_POST['delete']) && isset($_POST['id'])){
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
            header("Location: index.php");
        }

        $query = "DELETE FROM ScheduledActivity WHERE ScheduledActivityID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        header("Location: index.php");
    }else if (isset($_GET['id'])){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
            header("Location: index.php");
        }

        $userquery = "SELECT * FROM ActivityList INNER JOIN ScheduledActivity ON ActivityList.ActivityListID=ScheduledActivity.ActivityListID WHERE ScheduledActivityID = :id LIMIT 1";
        $userstatement = $db->prepare($userquery);
        $userstatement->bindValue(':id', $id, PDO::PARAM_INT);
        $userstatement->execute();
    
        $userrow = $userstatement->fetch();
        if ($_SESSION['access'] >= 3 || $_SESSION['user'] === $userrow['owner']) {
            $activityquery = "SELECT * FROM ActivityList WHERE Active = 'y' ORDER BY ActivityName";

            $activitystatement = $db->prepare($activityquery);

            $activitystatement->execute();
        } else {
            header("Location: noaccess.php");
            exit;
       }
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Activity</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
        <?php include('activity_nav.php'); ?>
		<form method="post">
			<form method="post" action="editevent.php" id="editevent">
            <label for="ActivityListID">Activity Name:</label>
            <select id="ActivityListID" name="ActivityListID">
                <?php while ($row = $activitystatement->fetch()): ?>
                    <option value="<?= $row['ActivityListID'] ?>" 
                        <?php if($row['ActivityListID'] == $userrow['ActivityListID']): ?>
                            SELECTED 
                        <?php endif ?>
                        ><?= $row['ActivityName'] ?></option>
                <?php endwhile ?>
            </select>
            <label for="ActivityDate">Date of Activity:</label>
            <input id="ActivityDate" name="ActivityDate" type="date">
            <label for="ActivityTime">Time of Activity:</label>
            <input id="ActivityTime" name="ActivityTime" type="time">
            <label for="Notes">Activity Notes:</label>
            <textarea id="Notes" name="Notes"><?= $userrow['Notes'] ?></textarea>
            <input type="hidden" name="id" value="<?= $userrow['ScheduledActivityID'] ?>" />
            <input type="submit" name="update" value="update" id="update"/>
            <input type="submit" name="delete" value="delete" id="delete" onclick="return confirm('Are you sure you wish to delete this event?')" />
		</form>
	</div>
</body>
</html>