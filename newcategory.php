<?php
	require 'connect.php';
	if ($_SESSION['access'] <= 3) {
  		header("Location: noaccess.php");
        exit;
	}

	if ($_POST ) {
		$CategoryName = filter_input(INPUT_POST, 'CategoryName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strlen($CategoryName) < 1 || strlen($CategoryName) > 100 ) {
        	header("Location: error.php?error=newcategory");
        	exit;
        }
		$query = "INSERT INTO categories (CategoryName) VALUES (:CategoryName)";
		$statment = $db->prepare($query);
			
		$statment->bindValue(':CategoryName', $CategoryName);
		
		if($statment->execute()){
			header("Location: categories.php");
			exit;
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>New Category</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
		<form method="post" action="newcategory.php" id="newcategory">
        	<label for="CategoryName">Category Name:</label>
        	<input id="CategoryName" name="CategoryName">
        	<input type="submit">
    	</form>
	</div>
</body>
</html>