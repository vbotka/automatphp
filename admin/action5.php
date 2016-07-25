<HTML>
<BODY>
<br>action5.php<br>
<?php
	$mysesh->AutomatModul = "action5.php";
?>
<BR>Select debug level<BR><HR>
    <FORM ACTION="automat.php" METHOD=POST>
     <TABLE>
     <TR><TD>dbglevel</TD><TD>uid sesshid</TD></TR>
     <TR><TD><SELECT NAME="dbglevel" SIZE=1 >
     <OPTION VALUE=0 SELECTED> Errors only
     <OPTION VALUE=1> Basic (sessions handling)
     <OPTION VALUE=8> ALL usr sessions
     <OPTION VALUE=16> All
     </SELECT></TD>
     <TD><SELECT NAME="session" SIZE=1 >
<?php
     $result = mysqli_query($mysesh->linkid,"select seshid,uid from actsessions");
$numrows = mysqli_num_rows($result);
for ( $i=0; $i<$numrows; $i++ ) {
	$array = mysqli_fetch_array($result);
	$seshid = (string)$array["seshid"];
	$uid = (string)$array["uid"];
	echo ("<OPTION VALUE=" . $seshid . ">" . $uid  . " " . $seshid );
	}
?>
</TD></TR>
</TABLE><BR><HR>
<INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>">
<INPUT TYPE=SUBMIT VALUE="OK">
</FORM>
</BODY>
</HTML>
<?php
	$mysesh->AutomatModul = "action5.php";
	$mysesh->NewAutomatState = 10;
// debug
	{ include ("fnc/actiondebug.php"); }
	$mysesh->AutomatSwitchState();
	$seshid = $mysesh->seshid;
	$mysesh->Destructor();
	unset($mysesh);
?>
