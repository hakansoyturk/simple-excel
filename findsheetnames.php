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

$sheetInfo = array();
$sheetIds=array();
$sheetNames = array();
$sheetRow = array();
$sheetColumn = array();
$i = 0;
while ($row = mysqli_fetch_array($allSheetResult, MYSQLI_ASSOC)) {
    $query99 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
    $query98 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
    $query97 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
    $query96 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
    $sheetInfo[$i] = mysqli_fetch_array($query99)[0];
    $sheetInfo[$i+1] = mysqli_fetch_array($query98)[1];
    $sheetInfo[$i+2] = mysqli_fetch_array($query97)[2];
    $sheetInfo[$i+3] = mysqli_fetch_array($query96)[3];
    $i+=4;
}

echo json_encode($sheetInfo);
?>