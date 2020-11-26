<?php
	require 'connect.php';

	if ($_SESSION['access'] <= 3) {
  		header("Location: noaccess.php");
        exit;
	}

	if ($_POST && isset($_POST['CategoryName']) && isset($_POST['id']) && isset($_POST['update']) ) {

		$CategoryName = filter_input(INPUT_POST, 'CategoryName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

        $query = "UPDATE categories SET CategoryName = :CategoryName WHERE CategoryID = :CategoryID";
        $statement = $db->prepare($query);
        $statement->bindValue(':CategoryName', $CategoryName);
        $statement->bindValue(':CategoryID', $id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: editcategory.php?id={$id}");
        exit;
    } else if (isset($_GET['id'])){
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

		$query = "SELECT * FROM categories WHERE CategoryID = :id";
    	$statement = $db->prepare($query);
    	$statement->bindValue(':id', $id, PDO::PARAM_INT);
    	$statement->execute();
	
    	$row = $statement->fetch();
    }

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Category</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
        <?php include('activity_nav.php'); ?>
		<form method="post">
			<label for="CategoryName">Category Name:</label>
        	<input id="CategoryName" name="CategoryName" value="<?= $row['CategoryName'] ?>">
        	<input type="hidden" name="id" value="<?= $row['CategoryID'] ?>" />
        	<input type="submit" name="update" value="update" id="update"/>
		</form>
	</div>
</body>
</html>