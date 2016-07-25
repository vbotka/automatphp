<HTML>
<BODY>
<?php
	echo ("<br>ERROR: automat reached default state<br>");

// debug
	$mysesh->err_level = 1;
	$mysesh->err="Automat. Deafult state reached. State: " . (string)$mysesh->AutomatState;
	$mysesh->err_no=207;
	$mysesh->DbgAddSqlLog();

	{ include ("fnc/sessioninfo.php"); }

// state 0 return	
	$mysesh->NewAutomatState = 0;
	$mysesh->AutomatSwitchState();

	$seshid = 0;
	$mysesh->Destructor();
	unset($mysesh);
?>
<BR>
Clearing seshid and switching to state 0
<BR><HR>
<A HREF="automat.php?<?php echo("seshid=$seshid")?>><br>OK</A>
</BODY>
</HTML>
