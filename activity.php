<?php
	require 'connect.php';

	if (isset($_GET['id'])){
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

		$query = "SELECT * FROM Categories INNER JOIN ActivityList ON Categories.CategoryID=ActivityList.Category WHERE ActivityListID = :id LIMIT 1";
    	$statement = $db->prepare($query);
    	$statement->bindValue(':id', $id, PDO::PARAM_INT);
    	$statement->execute();
	
    	$row = $statement->fetch();
    } else {
    	header("Location: index.php");
    	exit;
    }

?>
<!DOCTYPE html>
<html>
<head>
	<title><?= $row['ActivityName'] ?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
		<h2><?= $row['ActivityName'] ?></h2>
		<h4>Information: <?= $row['ActivityInfo'] ?></h4>
		<h4>Participants: <?= $row['Participants'] ?></h4>
		<h4>Category: <?= $row['CategoryName'] ?></h4>
		<?php if($row['Image']): ?>
			<img src="uploads/<?= $row['Image'] ?>">
		<?php endif ?>
		<?php if($_SESSION['access'] >= 3): ?>
			<p><a href="editactivity.php?id=<?= $row['ActivityListID'] ?>">Edit</a></p>
		<?php endif ?>
	</div>
</body>
</html>