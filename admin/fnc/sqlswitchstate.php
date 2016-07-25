<?php
$query="update actsessions ";
$query.="set state=$NewAutomatState ";
$query.="where seshid='$mysesh->seshid'";
echo ("query: $query");
$result=mysqli_query($mysesh->linkid,$query);
return;
?>
