<HTML>
<BODY>
<br>action9.php<br>
<?php
	$mysesh->AutomatModul = "action9.php";
	$mysesh->NewAutomatState = 0;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();
// nothing will be left in the tables but we done it anyway

	/* SHOW actsessions AND ASK */
	/* STOP SYSTEM EXECUTION */
	/* BACKUP TABLES */

	{ include ("fnc/sqlinitdb.php"); }

	$seshid = 0;
	$mysesh->Destructor();
	unset($mysesh);


?>
<BR><HR>
Tabels droped and created. Clearing seshid and switching to state 0.
<BR><HR>
<FORM ACTION="automat.php" METHOD=POST>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>
