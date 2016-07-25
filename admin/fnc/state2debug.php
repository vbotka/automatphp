<?php

$mysesh->err_level = 1;

$mysesh->err  = "Action Selector. selected action: ";
$mysesh->err .= (string)$mysesh->AutomatAction;
$mysesh->err .= " new automat state: ";
$mysesh->err .= (string)$mysesh->AutomatState;

$mysesh->err_no=0;
$mysesh->DbgAddSqlLog();

return;

?>
