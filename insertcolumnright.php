<?php
$sid = $_POST["sid"];
$row = $_POST["row"];
$column = $_POST["column"];
$newColumn = $column+1;
function openConnection(){
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$conn = openConnection();
mysqli_query($conn, "UPDATE sheets SET cols='$newColumn' WHERE sid='$sid'");
?>