<?php 
include_once("DatabaseFunctions.php");

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $target_dir = "../../php/photos/";
    $target_file = $target_dir . $_POST['Name']. ".".pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
        
        $uploadOk = 1;
      } else {
        echo "no size";
        $uploadOk = 0;
      }
    }
    
    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
        UploadPhotoToDB($conn, $_POST['Name'], $target_file, $_POST['Who']);
    } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
}

