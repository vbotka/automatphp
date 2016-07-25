<?php
	$mysesh->err_level = 1;
	$mysesh->err  = "Pre: " . (string)$mysesh->PreviousAutomatState;
	$mysesh->err .= " Act: " . (string)$mysesh->AutomatAction;
	$mysesh->err .= " Now: " . (string)$mysesh->AutomatState;
	$mysesh->err_no=0;
	$mysesh->DbgAddSqlLog();
	return;
?>

