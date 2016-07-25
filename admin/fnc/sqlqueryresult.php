<?php
$color1 = "\"#56787A\"";
$color2 = "\"#466263\"";
$sqlhost="localhost";
$user="admin";
$password="admin";
$database="automatphp";
mysqli_connect($sqlhost,$user,$password);
mysqli_select_db($database);
$sqlquery = stripSlashes($sqlquery) ;
$result = mysqli_query($mysesh->linkid,$sqlquery);
?>
Results of query <B>
<?php echo($sqlquery); ?>
</B><HR>
<?php
    if ($result == 0):
        echo("<B>Error " . mysqli_errno($mysesh->linkid) . ": " . mysqli_error($mysesh->linkid) . "</B>");
elseif (mysqli_num_rows($result) == 0):
    echo("<B>Query executed successfully!</B>");
else:
?>

<TABLE BORDER=1>
   <THEAD>
      <TR>
<?php
        for ($i = 0; $i < mysqli_num_fields($result); $i++) {
echo("<TH><SMALL>" . mysqli_field_name($result,$i) . "</SMALL></TH>");
}
?>
      </TR>
   </THEAD>
   <TBODY>

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
   </TBODY>
</TABLE>
<?php
endif
?>
