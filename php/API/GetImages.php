<?php 
include_once("DatabaseFunctions.php");
$page = @$_GET['Page'];
if(strlen($page) <= 0) $page = 0;
$capacity = @$_GET['Capacity'];
if(strlen($capacity) <= 0) $capacity = 10;
$pages = GetAllPhotos($conn, $page, $capacity);
$JsonObject = array();
for ($i=0; $i < count($pages); $i++) { 
    $JsonObject[$i]["Id"] = $pages[$i][0];
    $JsonObject[$i]["Name"] = $pages[$i][1];
    $JsonObject[$i]["Path"] = $pages[$i][2];
    $JsonObject[$i]["Who"] = $pages[$i][3];
}
echo json_encode($JsonObject);