<?php

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
				imagepng($newImage,$file);
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
				$new_image_path = file_upload_path($newImage);

				imagejpeg($newImage,$new_image_path);


				// $image_filename = $_FILES['image']['name'];
    // 			$temp_image_path = $_FILES['image']['tmp_name'];
    // 			$new_image_path = file_upload_path($image_filename);
				// move_uploaded_file($temp_image_path, $new_image_path);
			}
		}

	}

	


	if ($_POST) {
		if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/jpeg' ) {

			print_r($_FILES);
			move_uploaded_file($_FILES['image']['tmp_name'], $_FILES['image']['name']);

			$file = $_FILES['image']['name'];

			Resize_Jpeg_Image($file,"600");

			echo "<img src='$file'>";
		}
		if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/png' ) {

			print_r($_FILES);
			move_uploaded_file($_FILES['image']['tmp_name'], $_FILES['image']['name']);

			$file = $_FILES['image']['name'];

			Resize_Png_Image($file,"600");

			// $image_filename = $_FILES['image']['name'];
   //  		$temp_image_path = $_FILES['image']['tmp_name'];
   //  		$new_image_path = file_upload_path($image_filename);
			// move_uploaded_file($temp_image_path, $new_image_path);

			echo "<img src='$file'>";
			
		}
	}


?>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="image"><br/>
	<input type="submit" name="post">
</form>