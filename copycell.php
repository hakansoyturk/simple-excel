<?php

$location = $_POST["location"];
function openConnection(){
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$conn = openConnection();
$record = mysqli_query($conn, "SELECT * FROM cells WHERE location='$location'");
echo mysqli_fetch_array($record)[2];
?>