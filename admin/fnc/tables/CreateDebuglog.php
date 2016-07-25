<?php
$query  = "create table debuglog (";
$query .= "time integer unsigned,";
$query .= "sid integer unsigned,";
$query .= "uid integer unsigned,";
$query .= "err integer(5),";
$query .= "msg varchar(255))";
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. debuglog table created.");
else
    echo ("<br>ERROR creating debuglog:<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
?> 
