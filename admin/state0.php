<HTML>
<BODY>
<br>state0.php<br>
<?php
	{ include ("fnc/sessioninfo.php"); }
	$mysesh->NewAutomatState = 2;
	$mysesh->AutomatSwitchState();
	$seshid = $mysesh->seshid;
	$mysesh->Destructor();
	unset($mysesh);
?>
<BR>Select action<BR><HR>
<FORM ACTION="automat.php" METHOD=POST>
<SELECT NAME="action" SIZE=1 >
<OPTION VALUE=0 SELECTED> None
<OPTION VALUE=1> PHP Information
<OPTION VALUE=2> SQL Administration
<OPTION VALUE=3> Clear debuglog table *WARNING*
<OPTION VALUE=4> Clear actual sessions table *DANGER*
<OPTION VALUE=5> Change debug level
<OPTION VALUE=6> Change expire time *WARNING*
<OPTION VALUE=7> Create tables *DANGER*
<OPTION VALUE=8> Upload user automat table *DANGER*
<OPTION VALUE=9> Test
</SELECT>
<BR><HR>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>

