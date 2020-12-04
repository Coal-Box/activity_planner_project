<?php
	require 'connect.php';

	if ($_POST) {
		$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);

		if (strlen($search) < 1){
			header("Location: index.php");
        	exit;
		}

		if ($category !== -1) {

			$search = '%'.$search.'%';
			$query = "SELECT * FROM ActivityList WHERE ActivityInfo LIKE :search AND Category = :category ";
    		$statement = $db->prepare($query);
	
    		$statement->bindValue(':search', $search);
    		$statement->bindValue(':category', $category);
	
    		$statement->execute();
		}else{

			$search = '%'.$search.'%';
			$query = "SELECT * FROM ActivityList WHERE ActivityInfo LIKE :search ";
    		$statement = $db->prepare($query);
	
    		$statement->bindValue(':search', $search);
	
    		$statement->execute();
    	}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
		
		<h2>See results below:</h2>
		<?php while ($row = $statement->fetch()): ?>
			<p><a href="activity.php?id=<?= $row['ActivityListID'] ?>"><?= $row['ActivityName'] ?></a></p><br/>
		<?php endwhile ?>
	</div>
</body>
</html>