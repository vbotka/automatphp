<?php
// Change debug level
	{ include ("fnc/sqlchangedebuglevel.php"); }

	$mysesh->AutomatModul = "action8.php";
	$mysesh->NewAutomatState = 0;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();
	return;
?>

