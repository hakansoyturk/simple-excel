<?php
function openConnection()
{
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$sid = $_POST["sid"];
$conn = openConnection();
$locationAndDataArray = array();
$sheetDatasQuery = "SELECT * FROM cells WHERE sid=$sid";
$cells = mysqli_query($conn,$sheetDatasQuery);
$i=0;
while($row = mysqli_fetch_array($cells,MYSQLI_ASSOC)){ //default sheet's datas
        $cellQuery = mysqli_query($conn,"SELECT * FROM cells WHERE cid=$row[cid]");
        $cellQuery2 = mysqli_query($conn,"SELECT * FROM cells WHERE cid=$row[cid]");
        $cellLocation = mysqli_fetch_array($cellQuery)[1];
        $cellData = mysqli_fetch_array($cellQuery2)[2];
        $locationAndDataArray[$i] = $cellLocation;
        $i++;
        $locationAndDataArray[$i]=$cellData;
        $i++;
}
echo json_encode($locationAndDataArray);
?>