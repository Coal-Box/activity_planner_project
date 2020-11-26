<?php
	require 'connect.php';

	if ($_SESSION['access'] <= 3) {
  		header("Location: noaccess.php");
        exit;
	}

	if ($_POST && isset($_POST['ActivityName']) && isset($_POST['ActivityInfo']) && isset($_POST['Active']) && isset($_POST['id']) && isset($_POST['update']) ) {

		$ActivityName = filter_input(INPUT_POST, 'ActivityName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ActivityInfo = filter_input(INPUT_POST, 'ActivityInfo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

        $query = "UPDATE ActivityList SET ActivityName = :ActivityName, ActivityInfo = :ActivityInfo, Active = :Active WHERE ActivityListID = :ActivityListID";
        $statement = $db->prepare($query);
        $statement->bindValue(':ActivityName', $ActivityName);
        $statement->bindValue(':ActivityInfo', $ActivityInfo);
        $statement->bindValue(':Active', $Active);
        $statement->bindValue(':ActivityListID', $id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: editactivity.php?id={$id}");
        exit;
    } else if ($_POST && isset($_POST['delete']) && isset($_POST['id'])){
    	$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    	if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

    	$query = "DELETE FROM ActivityList WHERE id = :id";
    	$statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        header("Location: index.php");

	} else if (isset($_GET['id'])){
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

		$query = "SELECT * FROM ActivityList WHERE ActivityListID = :id";
    	$statement = $db->prepare($query);
    	$statement->bindValue(':id', $id, PDO::PARAM_INT);
    	$statement->execute();
	
    	$row = $statement->fetch();
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
			<label for="ActivityName">Activity Name</label>
        	<input id="ActivityName" name="ActivityName" value="<?= $row['ActivityName'] ?>">
        	<label for="ActivityInfo">Activity Info</label>
        	<textarea id="ActivityInfo" name="ActivityInfo"><?= $row['ActivityInfo'] ?></textarea>
        	<label for="Active">Active?</label>
        	<select id="Active" name="Active">
        		<option value="y">Yes</option>
        		<option value="n">No</option>
        	</select>
        	<input type="hidden" name="id" value="<?= $row['ActivityListID'] ?>" />
        	<input type="submit" name="update" value="update" id="update"/>
        	<input type="submit" name="delete" value="delete" id="delete" onclick="return confirm('Are you sure you wish to delete this activity? With content vaulting activities can return in later seasons so consider just changing Active to no. ')" />
		</form>
	</div>
</body>
</html>