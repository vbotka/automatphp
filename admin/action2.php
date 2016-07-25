<HTML>
<BODY>
<br>action2.php<br>
<?php

	$mysesh->AutomatModul = "action2.php";
	$mysesh->NewAutomatState = 8;
// debug
	{ include ("fnc/actiondebug.php"); }

	$mysesh->AutomatSwitchState();
	$seshid = $mysesh->seshid;
	$mysesh->Destructor();
	unset($mysesh);
?>
<BR>
<FORM ACTION="automat.php" METHOD=POST>
Please select the database for the query:<BR><BR>
<SELECT NAME="sqlhost" SIZE=1 >
<OPTION>localhost
<OPTION>mysql.hostcentric.net
</SELECT><BR><HR>
Please input the SQL query to be executed:<BR><BR>
<TEXTAREA NAME="sqlquery" COLS=50 ROWS=10></TEXTAREA>
<BR><HR>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="Execute query">
</FORM>
</BODY>
</HTML>




