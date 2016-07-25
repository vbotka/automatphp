<?php
$query  = "create table services (";
$query .= "sid integer unsigned,";
$query .= "sname varchar(255))";
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. services table created.");
else
    echo ("<br>ERROR creating services:<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");

?>
