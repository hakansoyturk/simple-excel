<?php
function openConnection()
{
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$conn = openConnection();
$fid=$_POST['fid'];
$allSheets = "SELECT * FROM sheets WHERE fid='$fid'";
$allSheetResult = mysqli_query($conn, $allSheets);
$allSheetResult2 = mysqli_query($conn, $allSheets);


$sheetNameArray = array();
$stateArray = array();
$i=0;
while ($row = mysqli_fetch_array($allSheetResult, MYSQLI_ASSOC)) {
    $sheetNameArray[$i]=$row["sname"];
    $i++;
}

$lastElementOfArray = end($sheetNameArray); //finding last element of sheetNameArray
$lastCharOfString = substr($lastElementOfArray,-1);
$lastCharOfString++;
$newSheetName = "Sheet$lastCharOfString";

$j=0;
while ($row2 = mysqli_fetch_array($allSheetResult2, MYSQLI_ASSOC)) {
    $stateArray[$j]=$row2["state"];
    $j++;
}
$lastElementOfState = end($stateArray);
$lastElementOfState++;
mysqli_query($conn,"INSERT INTO sheets VALUES(NULL,'$newSheetName',10,10,$lastElementOfState,$fid)");
echo $newSheetName;
?>