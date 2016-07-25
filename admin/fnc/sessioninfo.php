<?php
	echo ("<br><b>Session info:</b><br><br>");

	$message  = "sqlhost: " . (string)$mysesh->sqlhost . "<br>";
	$message .= "sqluser: " . (string)$mysesh->sqluser . "<br>";
	$message .= "sqldb: " . (string)$mysesh->sqldb . "<br>";
	$message .= "seshid: " . (string)$mysesh->seshid . "<br>";
	$message .= "err: " . (string)$mysesh->err . "<br>";
	$message .= "err_no: " . (string)$mysesh->err_no . "<br>";
	$message .= "userid: " . (string)$mysesh->userid . "<br>";
	$message .= "AutomatState: " . (string)$mysesh->AutomatState . "<br>";
	$message .= "PreviousAutomatState: " . (string)$mysesh->PreviousAutomatState . "<br>";
	$message .= "DebugLevel: " . (string)$mysesh->DebugLevel . "<br>";
	$message .= "OS: " . getenv("OS") . "<br>";
	echo ( $message );

	return;
?>

