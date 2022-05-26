<?php
function openConnection()
{
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$fid = $_POST["fid"];
$conn= openConnection();
$allSheets = "SELECT * FROM sheets WHERE fid='$fid'";
$allSheetResult = mysqli_query($conn, $allSheets);

$sheetNames = array();
$i = 0;
while ($row = mysqli_fetch_array($allSheetResult, MYSQLI_ASSOC)) {
    $query99 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
    $sheetNames[$i] = mysqli_fetch_array($query99)[0];
    $i++;
}

echo json_encode($sheetNames);
?>