<?php
$query  = "create table groups (";
$query .= "gid integer unsigned,";
$query .= "gname varchar(255))";
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. groups table created.");
else
    echo ("<br>ERROR creating groups:<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");

?>
