<?php
	require 'connect.php';

	if ($_POST) {
		$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		if (strlen($search) < 0){
			header("Location: index.php");
        	exit;
		}

	$query = "SELECT * FROM ActivityList WHERE ActivityInfo LIKE '%:search%' ";
    $statement = $db->prepare($query);

    $statement->bindValue(':search', $search);

    $statement->execute();
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
			<a href=""></a><br/>
		<?php endwhile ?>
	</div>
</body>
</html>