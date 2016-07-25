<?php
// get POST variables
// var_dump($_REQUEST);
extract($_REQUEST);
// echo ( "<br> Usrautomat started. seshid: " . $seshid . "action: " . $action .  " <br>" );
// globals
$DEBUGLEVELAUTH = 6;
$DEBUGLEVELERROR = -1;
//
require ("classes/UsrSessions.php");
require ("classes/Graph.php");
require ("classes/SymbolTable.php");
require ("fnc/AuthSystem.php");
//
$mysesh = new UsrSession($seshid,$cid);
// new session created
if ( $mysesh->AutomatState == 0 ) {
    if (isset($cid)) {
        /* !!! EXTERNAL ACCESS  */
        $mysesh->AutomatState = 100;
        $mysesh->AutomatAction = 1;
    } else {
        $mysesh->AutomatAction = 1;
    }
} else {
    $mysesh->AutomatAction = $action;
}
// debug
$mysesh->err_level = 8;
$mysesh->err  = "Automat restarted. State: ";
$mysesh->err .= (string)$mysesh->AutomatState;
$mysesh->err .= " seshid: " . (string)$mysesh->seshid;
$mysesh->err_no = 0;
$mysesh->DbgAddSqlLog();

// Selector
{ include ("StateSelector.php"); }
if ($mysesh->AutomatState < 0)
{ include ("StateErr.php"); }
else {
    { include ("StatePre.php"); }
    { include ("StatePost.php"); }
}
?>
