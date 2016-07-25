<TABLE BORDER=1>
<?php

	$mysesh->AutomatFetchState(0);
	for ($i = 0; $i < $mysesh->AutomatDataRows; $i++) {
		echo("<TR>");
		$row_array = mysqli_fetch_row($mysesh->AutomatData);
		for ($j = 0; $j < 5; $j++) {
			echo("<TD><SMALL><SUP>" . $row_array[$j] . "</SMALL></SUP></TD>");
		}
		echo("</TR>");
	}

?>
</TABLE>

