<?php
$query  = "create table user (";
$query .= "user_id int not null auto_increment primary key,";
$query .= "user_name text,";
$query .= "real_name text,";
$query .= "email text,";
$query .= "password text,";
$query .= "remote_addr text,";
$query .= "confirm_hash text,";
$query .= "is_confirmed int not null default 0,";
$query .= "new_email text,";
$query .= "group_id int not null default 2,";
$query .= "state int not null default 0,";
$query .= "log_level int not null default 0)";
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. user table created.");
else
    echo ("<br>ERROR creating user:<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
?> 
