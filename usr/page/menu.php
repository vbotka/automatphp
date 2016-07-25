<?php
	$mysesh->err_level = 8;
	$mysesh->err  = "Menu.php State: ";
	$mysesh->err .= (string)$mysesh->AutomatState;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();
// get automat state menu data
	$mysesh->AutomatFetchState(-1);
	for ($i = 0; $i < $mysesh->AutomatDataRows; $i++) {
		$row_array = mysqli_fetch_array($mysesh->AutomatData);
		$value = $row_array['action'];
		$text = $row_array['menutext'];
		$state = $row_array['state'];
		{ include ("page/menuitem.php");}
	}
	return;
?>





