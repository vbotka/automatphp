<?php
$mysesh->AutomatModul = "Selector";
$mysesh->err_level = 1;
$mysesh->err  = (string)$mysesh->AutomatModul;
$mysesh->err .= " action selected: " . (string)$mysesh->AutomatAction;
$mysesh->err_no = 0;
$mysesh->DbgAddSqlLog();
// get automat state data
if ($mysesh->AutomatState == "999999") {
    $mysesh->NewAutomatState = $mysesh->AutomatAction;
} else {
    $mysesh->AutomatFetchState($mysesh->AutomatAction);
    if (!($mysesh->AutomatDataRows >  0)) {
        $mysesh->NewAutomatState = -1;
    } else {
        $row_array = mysqli_fetch_array($mysesh->AutomatData);
        $mysesh->NewAutomatState = $row_array['state'];
    }
}
$mysesh->AutomatSwitchState();
{ include ("fnc/actiondebug.php"); }
return;
?>
