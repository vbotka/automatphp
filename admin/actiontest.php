<HTML>
<BODY>
<br>actiontest.php<br>
<br><hr>
<img src="test/testjpeg.php?ABcdEf" border="1" height="30" width="70">
<br><hr>
<?php
	/* TEST MAIL */
	/* mail("solidnet", "subject", "test. vlado");*/

	$mysesh->AutomatModul = "actiontest.php";
	$mysesh->NewAutomatState = 0;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();
	$seshid = $mysesh->seshid;
	$mysesh->Destructor();
	unset($mysesh);
?>
<BR>
Testing. Switching to state 0
<BR><HR>
<FORM ACTION="automat.php" METHOD=POST>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>

