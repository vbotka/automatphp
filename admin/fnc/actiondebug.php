<?php
$mysesh->err_level = 1;
$mysesh->err = (string)$mysesh->AutomatModul . " Change state from " . (string)$mysesh->AutomatState . " to " . (string)$mysesh->NewAutomatState;
$mysesh->err_no=0;
$mysesh->DbgAddSqlLog();
return;
?>
