<?php
$query  = "create table orders (";
$query .= "oid int not null auto_increment primary key,";
$query .= "cid integer unsigned,";
$query .= "created integer unsigned,";
$query .= "message text)";
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. orders table created.");
else
    echo ("<br>ERROR creating orders:<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");

?>
