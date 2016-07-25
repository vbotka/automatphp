<?php
$query  = "create table customers (";
$query .= "cid integer not null auto_increment primary key,";
$query .= "uid integer unsigned,";
$query .= "sid integer unsigned,";
$query .= "company varchar(255),";
$query .= "city varchar(255),";
$query .= "zip varchar(255),";
$query .= "street varchar(255),";
$query .= "phone varchar(255),";
$query .= "fax varchar(255),";
$query .= "email varchar(255),";
$query .= "www text,";
$query .= "category text,";
$query .= "created integer unsigned,";
$query .= "state integer default '0',";
$query .= "supported integer default '0',";
$query .= "note text)";
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. customers table created.");
else
    echo ("<br>ERROR creating customers:" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
?> 
