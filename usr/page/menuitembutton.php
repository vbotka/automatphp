<?php
if ( $state == $mysesh->AutomatState )
	$button = "images/ButtonOff.jpg";
else
	$button = "images/ButtonOn.jpg";
?>
<INPUT TYPE=IMAGE SRC="<?php print $button ?>">
