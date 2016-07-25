<?php
for ($i = 0; $i < mysqli_num_rows($result); $i++) {
	if (( $i % 2) == 0) {
		echo("\n<TR BGCOLOR=" . $color1 . ">");
	} else {
		echo("\n<TR BGCOLOR=" . $color2 . ">");
	}
	$row_array = mysqli_fetch_row($result);
	for ($j = 0; $j < mysqli_num_fields($result); $j++) {
		echo("<TD><SMALL>" . $row_array[$j] . "</SMALL></TD>");
	}
	echo("</TR>");
}
?>