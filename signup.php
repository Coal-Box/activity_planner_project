<?php
	require 'connect.php';

	if ($_POST) {
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = $_POST['password'];
        $repassword = $_POST['password'];
        if ($password === $repassword) {
        	$hashpass = password_hash($password, PASSWORD_DEFAULT);
        } else {
        	header("Location: error.php?error=signup");
        	exit;
        }
        if (strlen($email) < 1 || strlen($username) < 1 || strlen($username) > 25 ) {
        	header("Location: error.php?error=signup");
        	exit;
        }
		$query = "INSERT INTO user (Email, Username, Password ) VALUES (:Email, :Username, :Password)";
		$statment = $db->prepare($query);

		$statment->bindValue(':Email', $email);
		$statment->bindValue(':Username', $username);
		$statment->bindValue(':Password', $hashpass);
		
		if($statment->execute()){
			header("Location: login.php");
			exit;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign-Up</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<form method="post" action="signup.php" id="signup">
        	<label for="email">Email:</label>
        	<input id="email" name="email" type="email">
        	<label for="username">Username:</label>
        	<input id="username" name="username" type="text">
        	<label for="password">Password:</label>
        	<input id="password" name="password" type="password">
        	<label for="re-password">Re-Enter Password:</label>
        	<input id="re-password" name="re-password" type="password">
        	<input type="submit">
    	</form>
	</div>
</body>
</html>