<FORM ACTION="automat.php" METHOD=POST>
<?php
if ($state == $mysesh->AutomatState) {
echo ("<TABLE CELLSPACING=0 CELLPADDING=0>");
} else {
echo ("<TABLE CELLSPACING=0 CELLPADDING=0>");  
} ?>
<TR>
<TD><?php /* { include ("page/menuitembutton.php");} */ ?> &nbsp</TD>
<TD style="width: 100%"><?php { include ("page/menuitemtext.php");} ?></TD>
<TD> &nbsp</TD>
</TR>
</TABLE>
<div><INPUT TYPE=HIDDEN NAME="action" VALUE="<?php print $value ?>"></div>
<div><INPUT TYPE=HIDDEN NAME="seshid" VALUE="<?php print $seshid ?>"></div>
</FORM>