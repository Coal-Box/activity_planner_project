<?php

	require 'connect.php';

	$query = "SELECT * FROM User WHERE Rank > 1";//have this be from a get so page is universal?
    $statement = $db->prepare($query);

    $statement->execute();

    //I think the below can be replaced with something better...
    $usernames = "";
    $passwords = "";

    while ($row = $statement->fetch()) {
    	$usernames .= $row['UserName'];
    	$usernames .= ',';

    	$passwords .= $row['Password'];
    	$passwords .= ',';
    }
    $usernames = rtrim($usernames, ',');
    $passwords = rtrim($passwords, ',');

    $userArry = explode(',', $usernames);
    $passArray = explode(',', $passwords);

	define('USER_LOGIN',$userArry);
	define('USER_PASSWORD',$passArray);


	if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || !in_array($_SERVER['PHP_AUTH_USER'], USER_LOGIN) || !in_array($_SERVER['PHP_AUTH_PW'], USER_PASSWORD)) {

		header('HTTP/1.1 401 Unauthorized');

		header('WWW-Authenticate: Basic realm="Event Planner"');

		exit("Access Denied: Username and password required.");

	}

	   

?>