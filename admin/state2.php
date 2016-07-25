<?php
	$mysesh->AutomatAction = $action;

	switch ($action) {
	case 0:
		$mysesh->AutomatState = 0;
		break;
	case 1: 
		$mysesh->AutomatState = 3;
		break;
	case 2: 
		$mysesh->AutomatState = 4;
		break;
	case 3: 
		$mysesh->AutomatState = 5;
		break;
	case 4: 
		$mysesh->AutomatState = 6;
		break;
	case 5: 
		$mysesh->AutomatState = 7;
		break;
	case 6: 
		$mysesh->AutomatState = 9;
		break;
	case 7: 
		$mysesh->AutomatState = 11;
		break;
	case 8: 
		$mysesh->AutomatState = 12;
		break;
	case 9: 
		$mysesh->AutomatState = 9999;
		break;
	default:
		$mysesh->AutomatState = 0;
		break;
	}

// debug
	{ include ("fnc/state2debug.php"); }

	return;
?>





