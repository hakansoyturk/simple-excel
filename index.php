<?php

function openConnection()
{
    $connect = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connect, "utf8");
    mysqli_select_db($connect, "excel");
    return $connect;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        td,
        th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Index</title>
</head>

<body>
    <table id="myDrive">
        <th>My Drive</th>
        <th><a onclick="showHiddenValues();">+</a></th>
        <tr>
            <td>File Name</td>
            <td>Last Modified</td>
        </tr>
        <?php
        $conn = openConnection();
        $allRecords = mysqli_query($conn, "SELECT * FROM files");

        while ($fileRow = mysqli_fetch_object($allRecords)) {
            echo "<tr>
            <td><a href='/excel.php?fid=$fileRow->fid'>$fileRow->fname</a></td>
            <td>$fileRow->lastmodified</td>
            </tr>";
        }
        ?>
    </table><br>
    <table id="inputTable" style="display: none;">
        <tr>
            <td><input id="fileName" type="text" placeholder="File Name"></td>
            <td><input id="create" onclick="addFile();" type="button" value="Create"></td>
        </tr>
    </table>
    <script>
        function showHiddenValues() {
            document.getElementById("inputTable").style = "display:block";
        }
        function addFile() {
            var fileName = document.getElementById("fileName").value;
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;
            $.ajax({
                cache: false,
                type: "POST",
                url: "addfile.php",
                data: {
                    fileName: fileName
                },
                success: function(fid) {
                    var tableRef = document.getElementById("myDrive");
                    var newRow = tableRef.insertRow(-1);
                    var newCell = newRow.insertCell(0);
                    var newCell2 = newRow.insertCell(1);
                    newCell.innerHTML = "<a href='/excel.php?fid=" + fid + "'>" + fileName + "</a>";
                    newCell2.innerHTML = today;

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
        }
    </script>
</body>

</html>