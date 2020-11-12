<?php
	require 'connect.php';

	$error = $_GET['error'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Error</title>
</head>
<body>
	<h2>There was a problem with your submission. Click <a href="<?= $error ?>.php">here</a> to return.</h2>
	<h4>Please ensure that all your information is valid.</h4>
</body>
</html>