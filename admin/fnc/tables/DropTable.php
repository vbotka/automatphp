<?php
$query = "drop table if exists " . $table;
/* echo ("<br>" . $query . " <br>"); */
$result = mysqli_query($mysesh->linkid,$query);
if ($result)
    echo ("<br>OK. " . $table . " table droped");
else
    echo ("<br>ERROR droping " . $table .":<br>" . (string)$mysesh->err=mysqli_error($mysesh->linkid) . "<br>");
?>
