<?php
$color1 = $mysesh->ColorRed;
$color2 = $mysesh->PageBgColor;
for ($i = 0; $i < mysqli_num_rows($result); $i++) {
/*
	if (( $i % 2) == 0) {
		echo("\n<TR BGCOLOR=" . $color1 . ">");
	} else {
		echo("\n<TR BGCOLOR=" . $color2 . ">");
	}
*/
	$row_array = mysqli_fetch_array($result);
	/* echo ("<tr><td>" . $row_array['state'] . "</td></tr>"); */
	if ( $row_array["state"] == 1 ) {
		echo("\n<TR BGCOLOR=" . $color1 . ">");
	} else {
		echo("\n<TR BGCOLOR=" . $color2 . ">");
	}
	for ($j = 0; $j < (mysqli_num_fields($result)-1); $j++) {
		echo("<TD><SMALL>" . $row_array[$j] . "</SMALL></TD>");
	}
	echo("<TD>");
// update item
	$item = $row_array[0];
	$action=7;
	$text="zmena";
	{ include ("page/postactionchange.php");}
	echo("</TD>");
	echo("<TD>");
// delete item
	$item = $row_array[0];
	$action=9;
	$text="smazat";
	{ include ("page/postactionchange.php");}
	echo("</TD>");
	echo("</TR>");
}
?>