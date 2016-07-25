<?php
// 1st delete all
$query  = "delete from ";
$query .= $table;
// echo ("<br>" . $query . " <br>");
$result = mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. " . $table . " records deleted.");
else
    echo ("<br>ERROR deleting records from " . $table . ":<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
// then load table
$query  = "load data infile ";
//	$query  = "load data local infile ";
$query .= "'" . $file . "'";
$query .= " into table ";
$query .= $table;
$query .= " fields terminated by '\\t'";
// echo ("<br>" . $query . " <br>");
$result = mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. " . $table . " table loaded from: " . $file);
else
    echo ("<br>ERROR loading " . $table . " table from " . $file . ":<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
?>
