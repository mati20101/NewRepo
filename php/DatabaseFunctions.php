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

function GetAllPhotos(mysqli $connect, int $page = 0){
    
    $query = 'SELECT * FROM photos LIMIT 10 OFFSET '.$page.';';

    $result = mysqli_query($connect, $query);

    $data = mysqli_fetch_all($result);

    return $data;
}

function UploadPhoto(mysqli $connect, string $_name, string $_path ){
    $name = mysqli_escape_string($connect, $_name);
    $path = mysqli_escape_string($connect, $_path);
    $query = 'INSERT INTO `photos` (`Name`, `Path`) VALUES '.
    '("'.$name.'", "'.$path.'")'
    .';';
    //INSERT INTO `photos` (`Id`, `Name`, `Path`, `Who`) VALUES (NULL, '', '', NULL);
    $result = mysqli_query($connect, $query) Or die(mysqli_error($connect));
}

