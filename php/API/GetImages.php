<?php 
include_once("DatabaseFunctions.php");
$page = @$_GET['Page'];
if(strlen($page) <= 0) $page = 0;
$pages = GetAllPhotos($conn, 10 * $page);

echo json_encode($pages);