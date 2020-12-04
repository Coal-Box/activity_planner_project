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

	


	if ($_POST) {
		if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/jpeg' ) {

			//print_r($_FILES);
			$file = $_FILES['image']['name'];
			$new_image_path = file_upload_path($file);
			move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path);

			Resize_Jpeg_Image($new_image_path,"600");

			//echo "<img src='uploads/$file'>";
		}
		if (isset($_FILES['image']) && $_FILES['image']['type'] == 'image/png' ) {

			//print_r($_FILES);
			$file = $_FILES['image']['name'];
			$new_image_path = file_upload_path($file);
			move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path);

			Resize_Png_Image($new_image_path,"600");

			//testing image
			// echo "<img src='uploads/$file'>";
			
		}
	}


?>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="image"><br/>
	<input type="submit" name="post">
</form>