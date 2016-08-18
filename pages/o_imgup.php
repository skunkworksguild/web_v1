<?php
// Guild Summary
// Making sure the date is correct
date_default_timezone_set("America/Detroit");

// Allow only admins
if($user->data['group_id'] != 9 && $user->data['group_id'] != 7){
	header('Location: http://www.skunkworksguild.com/');
}

/*
echo '<pre>';
print_r($_POST);
print_r($_FILES);
echo '</pre>';
*/

$fUploaded = false;  // flag to show preview of upload file
//////////////////////////////////////////////////////////////////////////////////

if(isset($_FILES['fname'])){


	// 1.  Check to seee if there was an error detected
	if($_FILES['fname']['error'] > 0){

	 	switch($_FILES['fname']['error']){

			case 1: echo 'File exceeded upload_max_filesize';
						 break;
			case 2: echo 'File exceeded MAX_FILE_SIZE';
						 break;
			case 3: echo 'File only partially uploaded';
						 break;
			case 4: echo 'No file uploaded';
						 break;
			case 6: echo 'Cannot upload file: No temp directory specified';
						 break;
			case 7: echo 'Upload failed: Cannot write to disk';
						 break;
			case 8: echo 'Upload failed: Extension not allowed';
						 break;
		}
	
		// Whenever something goes wrong call exit.  This will stop the rest of the page from loading
		exit; 
	}

	
    // 2. Next, make sure the file uploaded is the type that is expected
	// In the example below this will make sure only jpg files are allowed
	// MIME TYPE CHECK
	
	/*
	if($_FILES['fname']['type'] != 'image/jpeg' &&
	   $_FILES['fname']['type'] != 'image/png' &&
	   $_FILES['fname']['type'] != 'image/gif'){
		echo 'Hey, this is not a jpeg/png/gif!';
		exit;
	}*/

	
	// 3. Specify where we want to place our files
	$upfile = 'images/news/' . $_FILES['fname']['name'];

	// 4. Save the file to the desired location
	// This checks to make sure the file we dealing with is the exact one we uploaded
	if(is_uploaded_file($_FILES['fname']['tmp_name'])){

	    // This moves the file from the tmp file to the permanent location
		if(move_uploaded_file($_FILES['fname']['tmp_name'], $upfile) == false){
			echo 'Problem: Could not move file to the destination directory<br>';
			exit;
		}

	}
	else {
		echo 'Problem: Possible file upload attack.<br>';
		echo 'File: ' . $_FILES['fname']['name'];
		exit;	
	}

	

	// This is a final check that strips converts our binary file to text, strips that text of php/html and then resets it as a binary file
	$contents = file_get_contents($upfile);
	$contents = strip_tags($contents);
	file_put_contents($_FILES['fname']['name'], $contents);

	$fUploaded = true;
}

?>

<div class="entireSection">
    <div class="title">
		<div class="newsSubject"><img src="images/gear6.png">News Image Upload (Officer)</div>
    </div>
   
    <div class="section">

<?php


if($user->data['group_id'] == 9 || $user->data['group_id'] == 7){


// Left Div
echo '<div style="float:left; width:400px; margin-right:25px;">';

	if(isset($_POST['newsfile'])){

		// Delete the selected file(s)

		echo "The following files were deleted:<br>";

		foreach($_POST['newsfile'] as $fileToDelete){

	       unlink("images/news/" . $fileToDelete);
	       echo '<span style="color:red; font-wieight:bold">'. $fileToDelete .'</span><br>';

		}


		echo "<br>";
	}



	echo '<b>FILE LISTING</b><br><i>(http://skunkworksguild.com/images/news/)</i><br><br>';
	$dir = dir("images/news");
	/*
	echo '<pre>';
	var_dump($dir);
	echo '</pre>';

	echo "Handle is $dir->handle<br>";
	echo "Uploading directory is $dir->path<br>";
	*/

	// create a form so we can delete files
	echo '<form action="" method="post">';
	echo '<input type="submit" name="dFile" value="Delete Selected Files">';
	echo '<br><br>';

    $i = 1;
	// Keep reading files in the directory and echoing them out
	while( $file = $dir->read() ){

		if($file != "." && $file != ".."){

		  //echo '<li><img width="50%" height="50%" src="images/news/' . $file . '"><br>';
		  echo '<input type="checkbox" name="newsfile['. $i .']" value="'. $file .'">' . '<a href="images/news/' . $file . '">' . $file . '</a>' .'<br>';
          echo  '<br>';
			
		}

		$i++;

	}

	
	echo '</form>';

echo '</div>';


// Right Div
echo '<div style="width="400px"; float: right;">';

?>
<b>UPLOAD FILES</b>
<br><br>
<form method="post" action="" enctype="multipart/form-data">
<input type="file" name="fname">
<br>
<input type="submit" value="Upload File to Server">

</form>

<?php




    if($fUploaded == true){

    	// Confirmation 
		echo 'File: ' . $_FILES['fname']['name'] . ' uploaded!<br>';
		// Once the file is uploaded we can use it however we please
		// Since this is an image, we can use the image tag to display it
		echo '<p>Preview of uploaded file</p>';
		echo '<img src="' . $upfile . '" height="30%"" width="30%">';
		echo '<br>';

	}


echo '</div>';


	// Close the object when we are done with it
	$dir->close();



}


?>	 
<div style="clear: both"></div>
    </div>
</div>