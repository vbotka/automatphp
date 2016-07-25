<?php
	$mysesh->err_level = 8;
	$mysesh->err  = "Body.php State: ";
	$mysesh->err .= (string)$mysesh->AutomatState;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();

	if ($mysesh->AutomatState < 0) {
		{include ("page/bodyerr.php");}
	} else {
		/* echo ("<TABLE BORDER=1 WIDTH=100%><TR><TD WIDTH=100%>"); */
		$file = "page/body/body" . $mysesh->AutomatState . ".php";
		{include ($file);}
		/* echo ("</TD></TR></TABLE>"); */
	}
?>

