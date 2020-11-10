<?php
	
	$currentdate = date('m/d/Y h:i:s a', time());

	$query = "SELECT * FROM ScheduledActivity ORDER BY id DESC LIMIT 5 WHERE ActivityDate  > " $date;
    $statement = $db->prepare($query);

    $statement->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
	</div>
</body>

</html>