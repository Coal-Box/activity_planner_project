<?php
	require 'connect.php';

	if ($_POST) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = "SELECT * FROM User WHERE Username = :username LIMIT 1";
        $statment = $db->prepare($query);

        $statment->bindValue(':username', $username);

        if ($statment->execute()) {
        	$row = $statment->fetch();

        	if (password_verify($_POST['password'], $row['Password'])) {
        		$_SESSION['user'] = $username;
        		$_SESSION['access'] = $row['Rank'];
        		header("Location: index.php");
        		exit;
        	} else {
        		header("Location: error.php?error=login");
        		exit;
        	}
        }
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
        <?php include('activity_nav.php'); ?>
		<form method="post" action="login.php" id="login">
        	<label for="username">Username:</label>
        	<input id="username" name="username" type="text">
        	<label for="password">Password:</label>
        	<input id="password" name="password" type="password">
        	<input type="submit">
    	</form>
	</div>
</body>
</html>