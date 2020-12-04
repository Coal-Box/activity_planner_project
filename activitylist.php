<?php
	require 'connect.php';

	if (isset($_GET['id'])){
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
			exit;
		}

		$query = "SELECT * FROM ActivityList WHERE Active = 'y' AND Category = :id";
    	$statement = $db->prepare($query);
    	$statement->bindValue(':id', $id);

    	$statement->execute();

    	$i=0;
	} else {
		header("Location: index.php");
    	exit;
	}

	// $query = "SELECT * FROM Categories INNER JOIN ActivityList ON Categories.CategoryID=ActivityList.Category WHERE ActivityListID = :id";
 //    $statement = $db->prepare($query);
 //    $statement->bindValue(':id', $id, PDO::PARAM_INT);
 //    $statement->execute();
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Activity List</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
		<h2 id="subtitle">All Available Activities</h2>
		<div class="container">
			<div class="row">
				<?php while ($row = $statement->fetch()): ?>
					<?php if($i % 3 == 0) :?>
						<div class="w-100"></div>
					<?php endif ?>
					<div class="col-sm">
						<h4><a href="activity.php?id=<?= $row['ActivityListID'] ?>"><?= $row['ActivityName'] ?></a></h4>
						<h5><?= $row['Participants'] ?> Player Activity</h5>
						<h5><?= $row['ActivityInfo'] ?></h5>
						<?php if($_SESSION['access'] >= 3): ?>
							<p><a href="editactivity.php?id=<?= $row['ActivityListID'] ?>">Edit</a></p>
						<?php endif ?>
					</div>
					<?php $i++ ?>
				<?php endwhile ?>
				<?php if($i % 3 !== 0) :?>
					<div class="col-sm"></div>
					<?php $i++ ?>
				<?php endif ?>
				<?php if($i % 3 !== 0) :?>
					<div class="col-sm"></div>
				<?php endif ?>
			</div>
		</div>
		<?php if($_SESSION['access'] >= 3): ?>
			<p><a href="newactivity.php">Add New</a></p>
		<?php endif ?>
	</div>
</body>
</html>