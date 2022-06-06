<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
    include_once("API/DatabaseFunctions.php");
if(count($_FILES) > 0){
$target_dir = "photos/";
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
    ?>
<style>
input{
  display: block;
}
.card {
  display: inline-block;
}
</style>

<div class="container">
  <div class="col-5">

<form action="index.php" method="post" enctype="multipart/form-data">
<div class="input-group mb-3">
  <input type="file" name="fileToUpload" class="form-control" id="inputGroupFile02">
  <label class="input-group-text" for="inputGroupFile02">Upload</label>
</div>
<div class="input-group mb-3">
  <input type="text" name="Name" class="form-control" placeholder="Image name" aria-label="Username" aria-describedby="basic-addon1">
</div>
<div class="input-group mb-3">
  <input type="text" name="Who" class="form-control" placeholder="Who" aria-label="Username" aria-describedby="basic-addon1">
</div>
 
<div class="col-auto">
    <button type="submit" name="submit" class="btn btn-primary mb-3">Send image</button>
  </div>
</form>
  </div>
  <div class="row justify-content-around">
    <div class="row justify-content-around">
      <div class="col-2" style="display: inline-block">
        <form action="index.php" method="GET">
        <input type="hidden" name="Page" value="<?php echo @$_GET['Page'] - 1; ?>">
          <input type="submit" name="PrevPage" value="<">
        </form>
      </div>
      <div class="col-2"style="display: inline-block">
        page <?php echo @$_GET['page']; ?>
      </div>
      <div class="col-2"style="display: inline-block;">
      <form action="index.php" method="GET">
        <input type="hidden" name="Page" value="<?php echo @$_GET['Page'] + 1; ?>">
          <input type="submit" name="NextPage" value=">">
        </form>
      </div>
    </div>
<?php 
$page = @$_GET['Page'];
if(strlen($page) <= 0) $page = 0;
$photos = GetAllPhotos($conn, 10 * intval($page));
for ($i=0; $i < count($photos); $i++) { 

  echo '<div class="card " style="width: 18rem;">';
  echo '<img src="'.$photos[$i][2].'" class="card-img-top" alt="...">';
  echo '<div class="card-body">';
  echo '  <p class="card-text">'.$photos[$i][1].'</p>';
  echo '  <p class="card-text">Uploaded by: <b>'.$photos[$i][3].'</b></p>';
  echo ' </div>';
  echo '</div>';
}
?>
</div>
</div>
</body>
</html>