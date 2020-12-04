<?php
	require 'connect.php';
	if ($_SESSION['access'] <= 3) {
  		header("Location: noaccess.php");
        exit;
	}

	function file_upload_path($original_filename) {
       $current_folder = dirname(__FILE__);
       
       $path_segments = [$current_folder, 'uploads', basename($original_filename)];
       
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }
	
	function Resize_Png_Image($file,$size){
		if (file_exists($file)) {
			$orginalImage = imagecreatefrompng($file);

			$orginalWidth = imagesx($orginalImage);
			$orginalHeight = imagesy($orginalImage);

			$ratio = $size/$orginalWidth;
			$newWidth = $size;
			$newHeight = $orginalHeight*$ratio;

			if ($newHeight > $size) {
				$ratio = $size/$orginalHeight;
				$newHeight = $size;
				$newWidth = $orginalWidth*$ratio;
			}

			if ($orginalImage) {
				$newImage = imagecreatetruecolor($newWidth, $newHeight);
				imagecopyresampled($newImage, $orginalImage, 0, 0, 0, 0, $newWidth, $newHeight, $orginalWidth, $orginalHeight);

				$new_image_path = file_upload_path($file);

				imagepng($newImage,$new_image_path);

			}
		}

	}

	function Resize_Jpeg_Image($file,$size){
		if (file_exists($file)) {
			$orginalImage = imagecreatefromjpeg($file);

			$orginalWidth = imagesx($orginalImage);
			$orginalHeight = imagesy($orginalImage);

			$ratio = $size/$orginalWidth;
			$newWidth = $size;
			$newHeight = $orginalHeight*$ratio;

			if ($newHeight > $size) {
				$ratio = $size/$orginalHeight;
				$newHeight = $size;
				$newWidth = $orginalWidth*$ratio;
			}

			if ($orginalImage) {
				$newImage = imagecreatetruecolor($newWidth, $newHeight);
				imagecopyresampled($newImage, $orginalImage, 0, 0, 0, 0, $newWidth, $newHeight, $orginalWidth, $orginalHeight);

				$new_image_path = file_upload_path($file);

				imagejpeg($newImage,$new_image_path);

			}
		}

	}

	if ($_POST ) {

		$ActivityName = filter_input(INPUT_POST, 'ActivityName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Participants = filter_input(INPUT_POST, 'Participants', FILTER_VALIDATE_INT);
        $ActivityInfo = filter_input(INPUT_POST, 'ActivityInfo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $CategoryID = filter_input(INPUT_POST, 'CategoryID', FILTER_VALIDATE_INT);
        if (strlen($ActivityName) < 1 || strlen($Participants) < 1 || strlen($ActivityName) > 50 || strlen($Participants) > 2 || $Participants < 1 || strlen($ActivityInfo) < 1 || strlen($ActivityInfo) > 200) {
        	header("Location: error.php?error=newactivity");
        	exit;
        }

		if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/jpeg' ) {

			//print_r($_FILES);
			$file = $_FILES['image']['name'];
			$new_image_path = file_upload_path($file);
			move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path);

			Resize_Jpeg_Image($new_image_path,"600");

			//echo "<img src='uploads/$file'>";

			$query = "INSERT INTO activitylist (ActivityName, Participants, ActivityInfo, Category, Image) VALUES (:ActivityName, :Participants, :ActivityInfo, :CategoryID, :Image)";
			$statment = $db->prepare($query);
			
			$statment->bindValue(':ActivityName', $ActivityName);
			$statment->bindValue(':Participants', $Participants);
			$statment->bindValue(':ActivityInfo', $ActivityInfo);
			$statment->bindValue(':CategoryID', $CategoryID);
			$statment->bindValue(':Image', $file);
		
			if($statment->execute()){
				header("Location: categories.php");
				exit;
			}

		}
		if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/png' ) {

			//print_r($_FILES);
			$file = $_FILES['image']['name'];
			$new_image_path = file_upload_path($file);
			move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path);

			Resize_Png_Image($new_image_path,"600");

			//testing image
			// echo "<img src='uploads/$file'>";

			$query = "INSERT INTO activitylist (ActivityName, Participants, ActivityInfo, Category, Image) VALUES (:ActivityName, :Participants, :ActivityInfo, :CategoryID, :Image)";
			$statment = $db->prepare($query);
			
			$statment->bindValue(':ActivityName', $ActivityName);
			$statment->bindValue(':Participants', $Participants);
			$statment->bindValue(':ActivityInfo', $ActivityInfo);
			$statment->bindValue(':CategoryID', $CategoryID);
			$statment->bindValue(':Image', $file);
		
			if($statment->execute()){
				header("Location: categories.php");
				exit;
			}
			
		} else{

			// $ActivityName = filter_input(INPUT_POST, 'ActivityName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   //      	$Participants = filter_input(INPUT_POST, 'Participants', FILTER_VALIDATE_INT);
   //      	$ActivityInfo = filter_input(INPUT_POST, 'ActivityInfo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   //      	$CategoryID = filter_input(INPUT_POST, 'CategoryID', FILTER_VALIDATE_INT);
   //      	if (strlen($ActivityName) < 1 || strlen($Participants) < 1 || strlen($ActivityName) > 50 || strlen($Participants) > 2 || $Participants < 1 || strlen($ActivityInfo) < 1 || strlen($ActivityInfo) > 200) {
   //      		header("Location: error.php?error=newactivity");
   //      		exit;
   //      	} else{
				$query = "INSERT INTO activitylist (ActivityName, Participants, ActivityInfo, Category) VALUES (:ActivityName, :Participants, :ActivityInfo, :CategoryID)";
				$statment = $db->prepare($query);
				
				$statment->bindValue(':ActivityName', $ActivityName);
				$statment->bindValue(':Participants', $Participants);
				$statment->bindValue(':ActivityInfo', $ActivityInfo);
				$statment->bindValue(':CategoryID', $CategoryID);
			
				if($statment->execute()){
					header("Location: categories.php");
					exit;
				}
			}
		}
	// }

	$catquery = "SELECT * FROM categories";
    $catstatement = $db->prepare($catquery);
    $catstatement->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<title>New Activity</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
		<?php include('activity_nav.php'); ?>
		<form method="post" action="newactivity.php" id="newactivity" enctype="multipart/form-data">
        	<label for="ActivityName">Activity Name:</label>
        	<input id="ActivityName" name="ActivityName"><br/>
        	<label for="Participants">Participants:</label>
        	<input id="Participants" name="Participants" type="number"><br/>
        	<label for="CategoryID">Category:</label>
        	<select id="CategoryID" name="CategoryID">
        		<?php while ($row = $catstatement->fetch() ): ?>
        			<option value="<?= $row['CategoryID'] ?>"><?= $row['CategoryName'] ?></option>
        		<?php endwhile ?>
        	</select><br/>
        	<label for="ActivityInfo">Activity Info:</label>
        	<textarea id="ActivityInfo" name="ActivityInfo"></textarea><br/>
        	<input type="file" name="image"><br/>
        	<input type="submit">
    	</form>
	</div>
</body>
</html>