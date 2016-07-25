<?php
if ($state == $mysesh->AutomatState) {
	echo ("<TABLE style=\"width: 100%\" cellspacing=0 cellpadding=0>");
	echo ("<TR><TD style=\"align: left\">");
} else {
	echo ("<TABLE style=\"width: 100%\" cellspacing=0 cellpadding=0>");
	echo ("<TR><TD style=\"align: right\">");
}
?>

<INPUT style="background: #458b74; color: #7fffd4" TYPE=SUBMIT VALUE="<?php print $text ?>">
</TD></TR></TABLE>
