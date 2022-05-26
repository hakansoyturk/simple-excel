<?php
session_start();
function openConnection()
{
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}
$fid = $_GET['fid'];
$conn = openConnection();

$fileQuery = "SELECT * FROM files WHERE fid='$fid'";
$fileResult = mysqli_query($conn, $fileQuery);
$fileName = mysqli_fetch_array($fileResult, MYSQLI_ASSOC)["fname"];


$allSheets = "SELECT * FROM sheets WHERE fid='$fid'";
$allSheetResult = mysqli_query($conn, $allSheets);
$allSheetResult2 = mysqli_query($conn, $allSheets);
$allSheetResult3 = mysqli_query($conn, $allSheets);



$sheetQuery = "SELECT * FROM sheets WHERE fid='$fid'";
$sheetResult = mysqli_query($conn, $sheetQuery);

//$sheetNames =  mysqli_fetch_array($sheetResult)[1];



$allSheetCount = mysqli_query($conn, "SELECT count(*) FROM sheets WHERE fid='$fid'");
$row = mysqli_fetch_array($allSheetCount);
$total = $row[0];



?>
<!DOCTYPE html>
<html lang="en">
<script>
    function assignIndexes(defaultSheetRowCount, defaultSheetColumnCount) {
        var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'];
        var aim = defaultSheetColumnCount + 1;
        var counter = 1;
        for (var l = 1; l <= defaultSheetColumnCount; l++) {
            var tr = document.getElementById(l);
            tr.innerHTML = letters[l - 1];
        }
        for (var k = 0; k < defaultSheetColumnCount; k++) {
            var td = document.getElementById(aim);
            td.innerHTML = counter;
            counter++;
            aim += defaultSheetColumnCount + 1;
        }
    }

    function fillCell(location, data) {
        document.getElementById(location).innerText = data;
    }
</script>

<head>

    <style>
        table {
            border: 1px solid black;
            table-layout: fixed;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            width: 100px;
            height: 20px;
            overflow: hidden;
        }

        .context-menu {
            position: absolute;
            text-align: center;
            background: lightgray;
            border: 1px solid black;
        }

        .context-menu ul {
            padding: 0px;
            margin: 0px;
            min-width: 150px;
            list-style: none;
        }

        .context-menu ul li {
            padding-bottom: 7px;
            padding-top: 7px;
            border: 1px solid black;
        }

        .context-menu ul li a {
            text-decoration: none;
            color: black;
        }

        .context-menu ul li:hover {
            background: darkgray;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Excel</title>
</head>

<body>

    <table id="list">
        <tr>
            <td><a href="/index.php">
                    <-- </a>
            </td>
            <td><?php echo $fileName; ?></td>
        </tr>
        <?php
        $sheetNames = array();
        $i = 0;
        while ($row = mysqli_fetch_array($allSheetResult2, MYSQLI_ASSOC)) {
            $sheetNames[$i] = $row['sname'];
            $i++;
        }
        $defaultSheetQuery = "SELECT * FROM sheets WHERE fid=$fid AND sname='$sheetNames[0]'";
        $defaultSheetResult = mysqli_query($conn, $defaultSheetQuery);
        $defaultSheetResult2 = mysqli_query($conn, $defaultSheetQuery);
        $defaultSheetResult3 = mysqli_query($conn, $defaultSheetQuery);
        $defaultSheetRowCount = mysqli_fetch_array($defaultSheetResult)[2];
        $defaultSheetColumnCount = mysqli_fetch_array($defaultSheetResult2)[3];
        $defaultSheetId = mysqli_fetch_array($defaultSheetResult3)[0];
        $counter = 0;
        for ($i = 0; $i <= $defaultSheetRowCount; $i++) {
            echo "<tr id='$i.tr'>";
            for ($j = 0; $j <= $defaultSheetColumnCount; $j++) {

                if ($counter <= $defaultSheetColumnCount) {
                    echo "<td id='$counter' oncontextmenu='rightClick(event,$counter);' ></td>                   
                    <div id='c.$counter' class='context-menu' style='display:none'>
                    <ul>
                        <li><a onclick='insertColumnRight($defaultSheetId,$counter,$defaultSheetRowCount,$defaultSheetColumnCount,$i)'>insert column right</a></li>
                        <li><a onclick='copyCell($counter)'>insert column left</a></li>
                        <li><a onclick='pasteCell($counter,$defaultSheetId)'>delete column</a></li>
                    </ul>
                    </div>
                    
                    ";
                } else {
                    echo "<td id='$counter' oncontextmenu='rightClick(event,$counter);' oninput='updateCell($defaultSheetId,$counter);'  contenteditable='true'>
               
                    </td>
                    <div id='c.$counter' class='context-menu' style='display:none'>
                    <ul>
                        <li><a onclick='cutCell($counter)'>Cut</a></li>
                        <li><a onclick='copyCell($counter)'>Copy</a></li>
                        <li><a onclick='pasteCell($counter,$defaultSheetId)'>Paste</a></li>
                    </ul>
                    </div>";
                }
                $counter++;
            }
            echo "</tr>";
        }
        echo "<script>assignIndexes($defaultSheetRowCount,$defaultSheetColumnCount)</script>";



        $defaultSheetDatasQuery = "SELECT * FROM cells WHERE sid=$defaultSheetId";
        $cells = mysqli_query($conn, $defaultSheetDatasQuery);
        while ($row = mysqli_fetch_array($cells, MYSQLI_ASSOC)) { //default sheet's datas
            $cellQuery = mysqli_query($conn, "SELECT * FROM cells WHERE cid=$row[cid]");
            $cellQuery2 = mysqli_query($conn, "SELECT * FROM cells WHERE cid=$row[cid]");
            $cellLocation = mysqli_fetch_array($cellQuery)[1];
            $cellData = mysqli_fetch_array($cellQuery2)[2];
            echo "<script>fillCell($cellLocation,\"$cellData\");</script>";
        }

        ?>
        <tr id="sheetRow">

            <?php

            $lastSid = 0;
            $y = 0;
            while ($row = mysqli_fetch_array($allSheetResult3, MYSQLI_ASSOC)) {
                $lastSid = $row['sid'] + 1;
                $y++;
            }
            $_SESSION["h"] = $lastSid;
            echo "<td><a onclick='addSheet($_SESSION[h],10,10,\"$fileName\",$fid,$y);'>+</a></td>";
            $_SESSION["h"]++;
            $x = 0;
            while ($row = mysqli_fetch_array($allSheetResult, MYSQLI_ASSOC)) {
                $query99 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
                $query98 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
                // $query97 = mysqli_query($conn, "SELECT * FROM sheets WHERE sid = '$row[sid]'");
                $rowNumber = mysqli_fetch_array($query99)[2];
                $columnNumber = mysqli_fetch_array($query98)[3];
                // $sheetId = mysqli_fetch_array($query97)[0];
                echo "<td id=td.$x onclick='drawSheet($row[sid],$rowNumber,$columnNumber,\"$fileName\",$fid)'>$row[sname]</td>";
                $x++;
            }

            ?>
        </tr>
    </table>

    <script>
        function insertColumnRight(sid,id,row,column,$rowid){
            $.ajax({
                cache: false,
                type: "POST",
                url: "insertcolumnright.php",
                data: {
                    id: id,
                    sid: sid,
                    row: row,
                    column: column
                },
                success: function(x) {

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })

        }
        function pasteCell(location,sid){
            var dataCookie = getCookie("copiedData");
            $.ajax({
                cache: false,
                type: "POST",
                url: "pastecell.php",
                data: {
                    location: location,
                    dataCookie: dataCookie,
                    sid: sid
                },
                success: function(data) {
                    document.getElementById(location).innerText = data;
                    var cookieArray = dataCookie.split(".");
                    if(cookieArray[3]=="cut"){
                        document.getElementById(cookieArray[2]).innerText="";
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
        }

        function copyCell(location) {
            $.ajax({
                cache: false,
                type: "POST",
                url: "copycell.php",
                data: {
                    location: location
                },
                success: function(data) {
        
                    setCookie("copiedData",data+".1."+location+".copy",7);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
        }

        function cutCell(location) {
            $.ajax({
                cache: false,
                type: "POST",
                url: "copycell.php",
                data: {
                    location: location
                },
                success: function(data) {
                    setCookie("copiedData",data+".2."+location+".cut",7);
                    document.getElementById(location).innerText = "";
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
        }

        var prevId=null;
        document.onclick = hideMenu2;
        //document.oncontextmenu = rightClick;
  
        function hideMenu2() {
            if(prevId!=null){
                document.getElementById("c."+prevId).style.display = "none";
                prevId=null;
            }
        }

        function hideMenu(pid) {
            document.getElementById("c."+pid).style.display = "none";
        }
  
        function rightClick(e,id) {
            e.preventDefault();
            if(prevId!=null){
                if (document.getElementById("c."+prevId).style.display == "block"){
                    hideMenu(prevId);
                    var menu = document.getElementById("c."+id)                 
                    menu.style.display = 'block';
                    menu.style.left = e.pageX + "px";
                    menu.style.top = e.pageY + "px";
                   
                }
            }else{
                    var menu = document.getElementById("c."+id)                 
                    menu.style.display = 'block';
                    menu.style.left = e.pageX + "px";
                    menu.style.top = e.pageY + "px";
                    prevId=id;
            }
        }


        function addSheet(lastSid, defaultSheetRowCount, defaultSheetColumnCount, fname, fid, y) {
            $.ajax({
                cache: false,
                type: "POST",
                url: "addsheet.php",
                data: {
                    fid: fid
                },
                success: function(sheetName) {
                    var row = document.getElementById("sheetRow");
                    var x = row.insertCell(-1);
                    x.innerHTML = sheetName;
                    x.id = "td." + y;
                    x.setAttribute("onclick", "drawSheet(" + lastSid + "," + defaultSheetRowCount + "," + defaultSheetColumnCount + ",'" + fname + "'," + fid + ");")
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
        }

        function updateCell(sid, location) {
            var data = document.getElementById(location).innerText;
            $.ajax({
                cache: false,
                type: "POST",
                url: "updatecell.php",
                data: {
                    location: location,
                    data: data,
                    sid: sid
                },
                success: function(x) {

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
        }

        function drawSheet(sid, row, column, fileName, fid) {
            $("#list tr").remove();
            var table = document.getElementById("list");
            var tableRow = table.insertRow(0);
            tableRow.id = "0.tr";
            var td = tableRow.insertCell(0);
            var td2 = tableRow.insertCell(1);
            td.innerHTML = "<a href='/index.php'> <-- </a>";
            td2.innerHTML = fileName;
            var counter = 0;
            for (var i = 1; i <= row + 1; i++) {
                var tableTr = table.insertRow(i);
                tableTr.id = i + ".tr";
                for (var j = 0; j <= column; j++) {
                    var tableTd = tableTr.insertCell(j);
                    if (counter <= column) {
                        tableTd.id = counter;
                    } else {
                        tableTd.id = counter;
                       // tableTd.innerHTML =  "<div id='c."+counter+"' class='context-menu' style='display:none'><ul><li><a onclick='cutCell("+counter+")'>Cut</a></li><li><a onclick='copyCell("+counter+")'>Copy</a></li><li><a onclick='pasteCell("+counter+","+sid+")'>Paste</a></li></ul></div>";
                        tableTd.setAttribute("contenteditable", true);
                        tableTd.setAttribute("oninput", "updateCell(" + sid + "," + counter + ");");
                        tableTd.setAttribute("oncontextmenu","rightClick(event,"+counter+");");
                      
                    }
                    counter++;
                }
            }
            counter = 0;
            assignIndexes(row, column);
            var sheetRow = table.insertRow(row + 2);
            sheetRow.id = "sheetRow";
            var sheetTd = sheetRow.insertCell(0);
            setCookie("ppkcookie", sid + 1, 7);
            var xz = getCookie('ppkcookie');
            sheetTd.innerHTML = "<a onclick=addSheet(" + xz + ",10,10,'" + fileName + "'," + fid + ",9);>+</a>"; //9 degisecek
            //echo "<td><a onclick='addSheet($lastSid,$defaultSheetRowCount,$defaultSheetColumnCount,\"$fileName\",$fid,$y);'>+</a></td>";
            $.ajax({
                cache: false,
                type: "POST",
                url: "findsheetnames.php",
                data: {
                    fid: fid
                },
                success: function(sheetInfo) {
                    infoArray = JSON.parse(sheetInfo);
                    for (var k = 1; k < infoArray.length; k += 4) {
                        var tdX = sheetRow.insertCell(Math.ceil(k / 4));
                        tdX.innerHTML = infoArray[k];
                        var x = Math.ceil(k / 4) - 1;
                        tdX.id = "td." + x;

                        tdX.setAttribute("onclick", "drawSheet(" + infoArray[k - 1] + "," + infoArray[k + 1] + "," + infoArray[k + 2] + ",'" + fileName + "'," + fid + ");");
                        //  tdX.setAttribute("onclick","setStyle("+this.id+")"); seçili sheetin boyanması
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })

            $.ajax({
                cache: false,
                type: "POST",
                url: "locationanddata.php",
                data: {
                    sid: sid
                },
                success: function(locationAndData) {
                    infoArray = JSON.parse(locationAndData);
                    for (var i = 0; i < (infoArray.length); i += 2) {
                        fillCell(infoArray[i], infoArray[i + 1]);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })


        }

        function setStyle(id) {
            alert(id);
            document.getElementById(id).style.backgroundColor = "red";
        }



        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function eraseCookie(name) {
            document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
    </script>

</body>

</html>