<?php
if ($mysesh->AutomatState == 100) {
	{include ("page/body/body100.php");}
	return;
} elseif ($mysesh->AutomatState == 101) {
	{include ("page/body/body101.php");}
	return;
} elseif ($mysesh->AutomatState == 102) {
	{include ("page/body/body102.php");}
	return;
} else {
	{include ("page/pagelocal.php");}
}
?>



