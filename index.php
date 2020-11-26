<?php

	require 'connect.php';
	
	$query = "SELECT * FROM ActivityList INNER JOIN ScheduledActivity ON ActivityList.ActivityListID=ScheduledActivity.ActivityListID WHERE ActivityDate > CURRENT_TIMESTAMP
 	ORDER BY ActivityDate LIMIT 5";
    $statement = $db->prepare($query);

    $statement->execute();

    //comments
    $comquery = "SELECT * FROM Comments WHERE Hidden='n' AND ComPage='index'
 	ORDER BY ComDate DESC LIMIT 20";
    $comstatement = $db->prepare($comquery);

    $comstatement->execute();

    if (isset($_POST['AddComment'])) {
    	$comment = filter_input(INPUT_POST, 'AddComment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	if (strlen($comment) > 1) {
    		$addquery = "INSERT INTO Comments (Author, Comment, ComPage) VALUES (:Author, :Comment, :ComPage)";
    		$addstatement = $db->prepare($addquery);

    		$addstatement->bindValue(':Author', $_POST['Author']);
    		$addstatement->bindValue(':Comment', $comment);
    		$addstatement->bindValue(':ComPage', $_POST['ComPage']);

    		if ($addstatement->execute()) {
    			header("Location: index.php");
				exit;
    		}
    	}
    	
    }

    if (isset($_POST['hide'])) {
    	$hidequery = "UPDATE Comments SET Hidden='y' WHERE CommentID = :CommentID";
    	$hidestatement = $db->prepare($hidequery);

    	$hidestatement->bindValue(':CommentID', $_POST['hide']);
    	if ($hidestatement->execute()) {
    		header("Location: index.php");
			exit;
    	}
    }

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
		<?php include('activity_nav.php'); ?>
		<h2 id="subtitle">Upcoming Activites:</h2>
		<div id="upcoming-activities">
			<?php while ($row = $statement->fetch()): ?>
				<table>
					<tr>
						<td colspan="8"><h2><?= $row['ActivityName'] ?></h2></td>
					</tr>
					<tr>
						<td ><span><h3>Date:</h3></span></td>
						<td colspan="2"><?= $row['ActivityDate'] ?></td>
						<td colspan="2"><span><h3>Avalable Spots:</h3></span></td>
						<td><?= $row['AvailableSpots'] ?></td>
						<td colspan="2"><a href="event.php?id=<?= $row['ScheduledActivityID'] ?>">Full Information</a></td>
					</tr>
					<tr>
						<td colspan="3"><h3>Notes:</h4></td>
						<td colspan="5" class="notes"><p><?= $row['Notes'] ?></p></td>
					</tr>
				</table>
			<?php endwhile ?>
		</div>
		<br/>
		<div id="comments">
			<table id="comments-tb">
				<tr>
					<td colspan="5"><h3>Comments</h3></td>
				</tr>
				<?php while ($comrow = $comstatement->fetch()): ?>
					<tr>
						<td><span>By:</span></td>
						<td colspan="3"><?= $comrow['Author'] ?></td>
						<?php if ($_SESSION['access'] >= 3): ?>
							<td><form method="post">
								<input type="hidden" name="hide" value="<?= $comrow['CommentID'] ?>">
								<input type="submit" value="hide">
							</form></td>
						<?php endif ?>
					</tr>
					<tr>
						<td colspan="5" class="notes"><?= $comrow['Comment'] ?></td>
					</tr>
				<?php endwhile ?>
			</table>
			<?php if($_SESSION['access'] >= 2): ?>
				<form method="post">
					<label for="AddComment">Add A Comment:</label>
        			<textarea id="AddComment" name="AddComment">Type Here...</textarea>
        			<input type="hidden" name="Author" value="<?= $_SESSION['user'] ?>"/>
        			<input type="hidden" name="ComPage" value="index"/>
        			<input type="submit">
				</form>
			<?php endif ?>
		</div>
	</div>
</body>
</html>