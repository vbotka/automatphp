<HTML>
<BODY>
<br>action3.php<br>
<?php
// Clear debuglog table

	/* WARNNING */

	{ include ("fnc/sqlcleardebuglog.php"); }

	$mysesh->AutomatModul = "action3.php";
	$mysesh->NewAutomatState = 0;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();
	$seshid = $mysesh->seshid;
	$mysesh->Destructor();
	unset($mysesh);
?>
<BR>
Debug log table cleared
<BR><HR>
<FORM ACTION="automat.php" METHOD=POST>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>
