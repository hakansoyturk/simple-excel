<?php

$location = $_POST["location"];
$data = $_POST["dataCookie"];
$sid = $_POST["sid"];

$rawData = explode(".", $data);
$pureData = $rawData[0];
$token = $rawData[1];
@$locationOfCuttingCell = $rawData[2];

function openConnection(){
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$conn = openConnection();
$record = mysqli_query($conn, "SELECT * FROM cells WHERE location='$location' and sid='$sid'");
if ($token == 1) {
    if (mysqli_num_rows($record) > 0) {
        mysqli_query($conn, "UPDATE cells SET data='$pureData' WHERE location=$location and sid='$sid'");
    } else {
        mysqli_query($conn, "INSERT INTO cells(cid,location,data,sid) VALUES(NULL,'$location','$pureData','$sid')");
    }
}else if ($token==2){  
    mysqli_query($conn,"DELETE FROM cells WHERE location = $locationOfCuttingCell and sid='$sid'");
    if (mysqli_num_rows($record) > 0) {
        mysqli_query($conn, "UPDATE cells SET data='$pureData' WHERE location=$location and sid='$sid'");
    } else {
        mysqli_query($conn, "INSERT INTO cells(cid,location,data,sid) VALUES(NULL,'$location','$pureData','$sid')");
    }
}
echo $pureData;
