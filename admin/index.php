<?php
	import_request_variables("P","rvar_");
//	echo ( "<br> SESHID:" . $rvar_seshid . "<br>" );
	$seshid = $rvar_seshid;
	$action = $rvar_action;
	$dbglevel = $rvar_dbglevel;
//	echo ( "<br> RemoteAddr:" . $_SERVER["REMOTE_ADDR"] . "<br>");
//	echo ( "<br>" . phpinfo() . "<br>" );
//	(string)$POSTseshid = $seshid;
	require ("classes/adminsessions.php");
	$mysesh = new AdminSession($seshid, $uid=0);

// debug
	$mysesh->err_level = 1;
	$mysesh->err  = "Automat restarted. State: ";
	$mysesh->err .= (string)$mysesh->AutomatState;
//	$mysesh->err .= " seshid: " . $POSTseshid;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();

	if ($mysesh->AutomatState == 2)
		{ include ("state2.php"); }
	if ($mysesh->AutomatState == 10)
		{ include ("action8.php"); }

	switch ($mysesh->AutomatState) {
// select action
	case 0: 
		{ include ("state0.php"); }
		break;
// PHP info return to state 0
	case 3: 
		{ include ("action1.php"); }
		break;
// SQL query return to state 8
	case 4: 
		{ include ("action2.php"); }
		break;
// Clear debuglog table and return to state 0
	case 5: 
		{ include ("action3.php"); }
		break;
// Clear actual sessions table and return to state 0
	case 6: 
		{ include ("action4.php"); }
		break;
// Change debug level and return to state 0
	case 7: 
		{ include ("action5.php"); }
		break;
// SQL query result returns to state 0
	case 8: 
		{ include ("action6.php"); }
		break;
// Change expire time and return to state 0
	case 9: 
		{ include ("actionnone.php"); }
		break;
// Create tables
	case 11: 
		{ include ("action9.php"); }
		break;
// Upload UsrAutomat
	case 12: 
		{ include ("action10.php"); }
		break;
// Change expire time and return to state 0
	case 9999: 
		{ include ("actiontest.php"); }
		break;
// error. never can go this far
	default:
		{ include ("statedefault.php"); }
		break;
	}

?>

