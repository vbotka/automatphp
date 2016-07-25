<?php
$query  = "create table usrautomat (";
$query .= "previous integer,";
$query .= "action integer,";
$query .= "state integer,";
$query .= "menu integer(1),";
$query .= "menutext text,";
$query .= "note text)";
/* echo ( "<br>" . $query . "<br>"); */
$result=mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. usrautomat table created.");
else
    echo ("<br>ERROR creating usrautomat:<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
?>
