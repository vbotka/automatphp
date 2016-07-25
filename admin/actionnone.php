<HTML>
<BODY>
<br>actionnone.php<br>
<?php

// NEW ACTION BEFORE DESTRUCTOR
	/* { include ("fnc/actionBEFORE.php"); } */

	$mysesh->AutomatModul = "actionnone.php";
	$mysesh->NewAutomatState = 0;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();
	$seshid = $mysesh->seshid;
	$mysesh->Destructor();
	unset($mysesh);

// NEW ACTION AFTER DESTRUCTOR
	/* { include ("fnc/actionAFTER.php"); } */

?>
<BR>
No action yet. Switching to state 0
<BR><HR>
<FORM ACTION="automat.php" METHOD=POST>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>

