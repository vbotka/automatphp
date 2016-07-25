<?php
$mysesh->DebugLevel = $dbglevel;
$query ="update actsessions ";
$query.="set dbglevel=$mysesh->DebugLevel ";
$query.="where seshid='$mysesh->seshid'";
$result=mysqli_query($mysesh->linkid,$query);
if (!$result) {
    $mysesh->err_level = -1;
    $mysesh->err=mysqli_error($mysesh->linkid);
    $mysesh->err_no=303;
    $mysesh->DbgAddSqlLog();
    $mysesh->AutomatState = -1;
    return;
}
return;
?>
