<?php

$location = $_POST["location"];
$data = $_POST["data"];
$sid = $_POST["sid"];
function openConnection(){
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$conn = openConnection();
$record = mysqli_query($conn, "SELECT * FROM cells WHERE location='$location' and sid='$sid'");
if (mysqli_num_rows($record) > 0) {
    mysqli_query($conn, "UPDATE cells SET data='$data' WHERE location=$location and sid='$sid'");
} else {
    mysqli_query($conn, "INSERT INTO cells(cid,location,data,sid) VALUES(NULL,'$location','$data','$sid')");
}
