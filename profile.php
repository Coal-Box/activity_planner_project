<?php
	require 'connect.php';
	if (!isset($_SESSION['user'])) {
  		header("Location: login.php");
        exit;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
	</div>
</body>
</html>