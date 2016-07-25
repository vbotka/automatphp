<HTML>
<BODY>
<br>action10.php<br>
<?php
	$mysesh->AutomatModul = "action10.php";
	$mysesh->NewAutomatState = 0;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();

	{ include ("fnc/sqluploadusr.php"); }

	$seshid = 0;
	$mysesh->Destructor();
	unset($mysesh);


?>
<BR><HR>
User automat table has been updated.
<BR><HR>
<FORM ACTION="automat.php" METHOD=POST>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>
