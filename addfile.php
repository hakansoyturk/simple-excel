<?php
function openConnection()
{
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$conn = openConnection();
$fileName=$_POST["fileName"];

$today=date("Y-m-d");
mysqli_query($conn,"INSERT INTO files VALUES(NULL,'$fileName','$today')");
$query = mysqli_query($conn,"SELECT * FROM files ORDER BY fid DESC LIMIT 1");
$record = mysqli_fetch_object($query);

mysqli_query($conn,"INSERT INTO sheets VALUES(NULL,'Sheet1',10,10,1,'$record->fid')");
echo $record->fid;
?>