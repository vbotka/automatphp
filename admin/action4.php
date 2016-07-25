<HTML>
<BODY>
<br>action4.php<br>
<?php
// Clear actsessions table

	/* DANGER */

	{ include ("fnc/sqlclearactsessions.php"); }

	$mysesh->AutomatModul = "action4.php";
	$mysesh->NewAutomatState = 0;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();
	$seshid = $mysesh->seshid;
	$mysesh->Destructor();
	unset($mysesh);
?>
<BR>
Actual sessions table cleared. Expect troubles :-)
<BR><HR>
<FORM ACTION="automat.php" METHOD=POST>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>
