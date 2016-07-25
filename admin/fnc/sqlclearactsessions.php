<?php
$query="delete from actsessions";
$result=mysqli_query($mysesh->linkid,$query);
if (!$result) {
    $mysesh->err_level = -1;
    $mysesh->err=mysqli_error($mysesh->linkid);
    $mysesh->err_no=302;
    $mysesh->DbgAddSqlLog();
    $mysesh->AutomatState = -1;
    return;
}
// debug
$mysesh->err_level = 1;
$mysesh->err = " *** ACTUAL SESSIONS TABLE CLEARED *** ";
$mysesh->err_no=0;
$mysesh->DbgAddSqlLog();
return;
?>
