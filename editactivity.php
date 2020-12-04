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

	if ($_POST && isset($_POST['ActivityName']) && isset($_POST['ActivityInfo']) && isset($_POST['Active']) && isset($_POST['id']) && isset($_POST['update']) ) {

		$ActivityName = filter_input(INPUT_POST, 'ActivityName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ActivityInfo = filter_input(INPUT_POST, 'ActivityInfo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $Active = filter_input(INPUT_POST, 'Active', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

        if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/jpeg' ) {

            //print_r($_FILES);
            $file = $_FILES['image']['name'];
            $new_image_path = file_upload_path($file);
            move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path);

            Resize_Jpeg_Image($new_image_path,"600");

            //echo "<img src='uploads/$file'>";

            $query = "UPDATE ActivityList SET ActivityName = :ActivityName, ActivityInfo = :ActivityInfo, Active = :Active, Image = :Image WHERE ActivityListID = :ActivityListID";
            $statement = $db->prepare($query);
            $statement->bindValue(':ActivityName', $ActivityName);
            $statement->bindValue(':ActivityInfo', $ActivityInfo);
            $statement->bindValue(':Active', $Active);
            $statement->bindValue(':Image', $file);
            $statement->bindValue(':ActivityListID', $id, PDO::PARAM_INT);

            $statement->execute();

        }
        if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/png' ) {

            //print_r($_FILES);
            $file = $_FILES['image']['name'];
            $new_image_path = file_upload_path($file);
            move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path);

            Resize_Png_Image($new_image_path,"600");

            //testing image
            // echo "<img src='uploads/$file'>";

            $query = "UPDATE ActivityList SET ActivityName = :ActivityName, ActivityInfo = :ActivityInfo, Active = :Active, Image = :Image WHERE ActivityListID = :ActivityListID";
            $statement = $db->prepare($query);
            $statement->bindValue(':ActivityName', $ActivityName);
            $statement->bindValue(':ActivityInfo', $ActivityInfo);
            $statement->bindValue(':Active', $Active);
            $statement->bindValue(':Image', $file);
            $statement->bindValue(':ActivityListID', $id, PDO::PARAM_INT);

            $statement->execute();
            
        } if ($_POST['ImageDelete']) {
            $deletedImage = 'uploads/'.$_POST['ImageDelete'];
            unlink($deletedImage);

            $query = "UPDATE ActivityList SET ActivityName = :ActivityName, ActivityInfo = :ActivityInfo, Active = :Active, Image = NULL WHERE ActivityListID = :ActivityListID";
            $statement = $db->prepare($query);
            $statement->bindValue(':ActivityName', $ActivityName);
            $statement->bindValue(':ActivityInfo', $ActivityInfo);
            $statement->bindValue(':Active', $Active);
            $statement->bindValue(':ActivityListID', $id, PDO::PARAM_INT);
            $statement->execute();
            
        }

        else{

            $query = "UPDATE ActivityList SET ActivityName = :ActivityName, ActivityInfo = :ActivityInfo, Active = :Active WHERE ActivityListID = :ActivityListID";
            $statement = $db->prepare($query);
            $statement->bindValue(':ActivityName', $ActivityName);
            $statement->bindValue(':ActivityInfo', $ActivityInfo);
            $statement->bindValue(':Active', $Active);
            $statement->bindValue(':ActivityListID', $id, PDO::PARAM_INT);
            $statement->execute();
        }

        header("Location: editactivity.php?id={$id}");
        exit;

    } else if ($_POST && isset($_POST['delete']) && isset($_POST['id'])){
    	$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  //   	if (!filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT)) {
		// 	header("Location: index.php");
		// }

    	$query = "DELETE FROM ActivityList WHERE ActivityListID = :ActivityListID";
    	$statement = $db->prepare($query);
        $statement->bindValue(':ActivityListID', $id, PDO::PARAM_INT);

        if($statement->execute()){
            header("Location: index.php");
        }

        

	} else if (isset($_GET['id'])){
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		if (!filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)) {
			header("Location: index.php");
		}

		$query = "SELECT * FROM ActivityList WHERE ActivityListID = :id";
    	$statement = $db->prepare($query);
    	$statement->bindValue(':id', $id, PDO::PARAM_INT);
    	$statement->execute();
	
    	$row = $statement->fetch();
    }

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Activity</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="boundry">
		<?php include('nav.php'); ?>
        <?php include('activity_nav.php'); ?>
		<form method="post" enctype="multipart/form-data">
			<label for="ActivityName">Activity Name</label>
        	<input id="ActivityName" name="ActivityName" value="<?= $row['ActivityName'] ?>">
        	<label for="ActivityInfo">Activity Info</label>
        	<textarea id="ActivityInfo" name="ActivityInfo"><?= $row['ActivityInfo'] ?></textarea>
            <?php if($row['Image']): ?>
                <label for="ImageDelete">Delete Image?</label>
                <input id="ImageDelete" type="checkbox" name="ImageDelete" value="<?= $row['Image'] ?>">
            <?php else: ?>
                <input type="file" name="image">
            <?php endif ?>
        	<label for="Active">Active?</label>
        	<select id="Active" name="Active">
        		<option value="y">Yes</option>
        		<option value="n">No</option>
        	</select>
        	<input type="hidden" name="id" value="<?= $row['ActivityListID'] ?>" />
        	<input type="submit" name="update" value="update" id="update"/>
        	<input type="submit" name="delete" value="delete" id="delete" onclick="return confirm('Are you sure you wish to delete this activity? With content vaulting activities can return in later seasons so consider just changing Active to no. ')" />
		</form>
	</div>
</body>
</html>