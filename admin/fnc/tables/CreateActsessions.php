<?php
$query  = "create table actsessions (";
$query .= "sid integer unsigned,";
$query .= "seshid varchar(32) not null primary key,";
$query .= "uid integer unsigned,";
$query .= "cid integer unsigned,";
$query .= "lastused integer unsigned,";
$query .= "state integer(5) default '0',";
$query .= "statefrom integer(5) default '0',";
$query .= "dbglevel integer(2) default '0',";
$query .= "remoteip varchar(32),";
$query .= "remotehost varchar(255),";
$query .= "uvalid integer(1) default '0',";
$query .= "confirmjpeg text)";
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. actsessions table created.");
else
    echo ("<br>ERROR creating actsessions:<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
?>
