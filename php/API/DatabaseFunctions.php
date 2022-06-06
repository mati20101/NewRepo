<?php 
$host = "localhost";
$login = "root";
$password = "";
$database = "GaleriaAPI";

$conn = mysqli_connect($host, $login, $password, $database);

if($conn == false || $conn == null){
    echo "Connect failure";
}
mysqli_query($conn, 'SET NAMES UTF8');

function GetAllPhotos(mysqli $connect, int $page = 0, int $capacity = 10){
    
    $query = 'SELECT * FROM photos LIMIT '.$capacity.' OFFSET '.$page.';';

    $result = mysqli_query($connect, $query);

    $data = mysqli_fetch_all($result);

    return $data;
}

function UploadPhotoToDB(mysqli $connect, string $_name, string $_path, string $_who){
    $name = mysqli_escape_string($connect, $_name);
    $path = mysqli_escape_string($connect, $_path);
    if (!strlen($_who) <= 0){
        $who = mysqli_escape_string($connect, $_who);
    }
    else $who = mysqli_escape_string($connect, "Anonymous");
    
    $query = 'INSERT INTO `photos` (`Name`, `Path`, `Who`) VALUES '.
    '("'.$name.'", "'.$path.'", "'.$who.'")'
    .';';
    //INSERT INTO `photos` (`Id`, `Name`, `Path`, `Who`) VALUES (NULL, '', '', NULL);
    $result = mysqli_query($connect, $query) Or die(mysqli_error($connect));
}