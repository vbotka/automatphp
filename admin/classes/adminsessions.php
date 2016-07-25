<?php

/* TODO:
	- add log to file for MySQL errors
	- scamble password to MySQL */

// Define the AdminSession class
class AdminSession {

// Define the properties:

// Globals
//	var $CONFPATH ="/usr/local/www/data/conf";
	var $CONFPATH ="/var/www/html/conf";
	var $sqlhost = "localhost";
	var $sqluser = "admin";
	var $sqlpass = "passwd";
	var $sqldb = "automatphp";
	var $linkid;
	var $sid;
	var $seshid;
	var $sessdata;
	var $err;
	var $err_no;
	var $err_level;
	var $expire_time = 900; // length of time until expiration in seconds
	var $uid;

// Added stuff:
	var $AutomatState;
	var $NewAutomatState;
	var $PreviousAutomatState;
	var $AutomatAction;
	var $AutomatModul;
	var $DebugLevel;
	var $DebugMessage;
	var $ConfirmJpeg;

// Define the constructor:
// ----------------------
function AdminSession($localSeshID, $localUserID) {
    //	 echo ( "<br> function AdminSession entered <br>" );
    //	 echo ( "<br> SESHID:" . $localSeshID . " UID:" . $localUserID . "<br>" );
	$this->seshid = $localSeshID;
	$this->uid = $localUserID;

// connect to MySQL
    // echo ( "<br> function mysqli_connect enterring <br>" );
    $this->linkid=mysqli_connect($this->sqlhost,$this->sqluser,$this->sqlpass);
    // echo ( "<br> function mysqli_connect returned <br>" );

// verify connection made
	if (!$this->linkid) {
		$this->err_level = -1;
		$this->err = mysqli_error($this->linkid);
		$this->err_no = 101;
		$this->AutomatState = -1;
		echo ( "<br> errno: 101" . " err:" . $this->err . "<br>" );
		return;
	}
    //	echo ( "<br> DB connected <br>" );

// select database
	$result=mysqli_select_db($this->linkid,$this->sqldb);

// unable to select db
	if (!$result) {
		$this->err_level = -1;
		$this->err=mysqli_error($this->linkid);
		$this->err_no=102;
		$this->AutomatState = -1;
		echo ( "<br> errno: 102" . " err:" . $this->err . "<br>" );
		return;
	}
    //	echo ( "<br> DB selected <br>" );


// check to see if verifying session or creating session
	if(!$this->seshid) {

// seshid 0 so creating
		$current = time();
		$random = $this->uid . $current;
		$this->sid = $current;
// admin session
		$this->seshid = "O" . substr(md5($random),1);
// user session
//		$this->seshid = substr(md5($random),1);
		$this->AutomatState = 0;
		$this->PreviousAutomatState = 0;
		$this->DebugLevel = 1;
// user connected from
		if (!$this->RemoteIP = getenv ("REMOTE_ADDR"))
			$this->RemoteIP = "10.1.0.10";
//		$this->RemoteIP = getenv ("REMOTE_ADDR");
		$this->RemoteHost = gethostbyaddr ($this->RemoteIP);
		$this->UserValid = 0;
		$this->ConfirmJpeg = "nothing";
//
		$query="insert into actsessions (sid,seshid,uid,lastused,state,statefrom,dbglevel,remoteip,remotehost,uvalid,confirmjpeg) values ('$this->sid', '$this->seshid','$this->uid', $current, $this->AutomatState, $this->PreviousAutomatState,  $this->DebugLevel, '$this->RemoteIP', '$this->RemoteHost', '$this->UserValid', '$this->ConfirmJpeg')";
        //        echo ( "<br> Query: " . $query . "<br>" );
		$result=mysqli_query($this->linkid,$query);
		if (!$result) {
            //            echo ( "<br> Create session error <br>" );
			$this->err_level = -1;
			$this->err = mysqli_error($this->linkid);
			$this->err_no = 104;
			$this->AutomatState = -1;
			return;
		}
        //       	echo ( "<br> Session created <br>" );
        
// finished session create return to script
		$this->err_level = 1;
		$this->err_no = 0;
		$this->err = "New session created. seshid: " . (string)$this->seshid;
		$this->DbgAddSqlLog();
		return;
	}

// not new session, verify
	$result=mysqli_query($this->linkid,"SELECT * FROM actsessions WHERE seshid='$this->seshid'");

// select failed
	if (!$result) {
		$this->err_level = -1;
		$this->err=mysqli_error($this->linkid);
		$this->err_no=103;
		$this->AutomatState = -1;
		return;
	}

// verify valid data returned
	$numrows=mysqli_num_rows($result);

// no rows returned, seshid not valid
	if (!$numrows) {
		$this->err_level = -1;
		$this->err="Session id not valid. seshid: " . (string)$this->seshid;
		$this->err_no=201;
		$this->DbgAddSqlLog();
		$this->AutomatState = -1;
		return;
	}

//get session data
	$this->sessdata = mysqli_fetch_array($result);

// restore returning session state
	$this->AutomatState = $this->sessdata["state"];
	$this->PreviousAutomatState = $this->sessdata["statefrom"];
	$this->DebugLevel = $this->sessdata["dbglevel"];
	$this->uid = $this->sessdata["uid"];
	$this->sid = $this->sessdata["sid"];

// check if the session expired
	$not_expired = $this->VerifyTime();
	if (!$not_expired) {
		$current = time();
		$lastused = $this->sessdata["lastused"];
		$this->err_level = -1;
		$this->err="Session has expired. lastUsed: $lastused expireTime: $this->expire_time currTime: $current";
		$this->err_no=202;
		$this->DbgAddSqlLog();
		$this->AutomatState = -1;
		return;
	}

// reset lastused to current
	$this->ResetTime("adminsesions.php");


// remove expired sessions

	$this->CleanUp();

// debug
	$this->err_level = 16;
	$this->err = "Session returned. State: " . (string)$this->AutomatState . " Previous: " . (string)$this->PreviousAutomatState;
	$this->err_no = 0;
	$this->DbgAddSqlLog();
}


// method to verify if the session expired:
// ---------------------------------------
function VerifyTime() {
	$current = time();
// check if the session has expired
	$this->err_level = 16;
	$this->err = "Verify expire. LastUsed: " . (string)$this->sessdata["lastused"] . " ExpireTime: " . (string)$this->expire_time . " Current: " . (string)$current ;
	$this->err_no = 0;
	$this->DbgAddSqlLog();
	if ($this->sessdata["lastused"]+$this->expire_time<$current) {
		return 0;
	}
	return 1;
}

// method to reset session clock counter:
// -------------------------------------
function ResetTime($module) {
	$this->module = $module;
	$current=time();
	$query="update actsessions ";
	$query.="set lastused=$current ";
	$query.="where seshid='$this->seshid'";
	$result=mysqli_query($this->linkid,$query);
// query failed
	if (!$result) {
		$this->err_level = -1;
		$this->err=mysqli_error($this->linkid);
		$this->err_no=203;
		$this->DbgAddSqlLog();
		$this->AutomatState = -1;
		return;
	}
// table actsessions updated
	$this->err_level = 16;
	$this->err = (string)$this->module . ": resetTime";
	$this->err_no = 0;
	$this->DbgAddSqlLog();
	return;
}

// method to clean stale sassions:
// ------------------------------
function CleanUp() {

// debug
	$this->err_level = 16;
	$this->err = "CleanUp entered";
	$this->err_no = 0;
	$this->DbgAddSqlLog();

	$current = time();
	$still_valid = $current - $this->expire_time;

// debug
	$this->err_level = 16;
	$this->err = "CleanUp: current: " . (string)$current . " expire: " . (string)$this->expire_time;
	$this->err_no = 0;
	$this->DbgAddSqlLog();

// copy stale sessions
	$query = "select sid,seshid,uid,lastused from actsessions where lastused<$still_valid";

// debug
	$this->err_level = 16;
	$this->err = "CleanUp query 01: " . (string)$query;
	$this->err_no = 0;
	$this->DbgAddSqlLog();

	$result = mysqli_query($this->linkid,$query);

// debug
	$this->err_level = 16;
	$this->err = "CleanUp query 01 executed";
	$this->err_no = 0;
	$this->DbgAddSqlLog();

	if (!$result) {
		$this->err=mysqli_error($this->linkid);
		$this->err_no=205;
		$this->DbgAddSqlLog();
		$this->AutomatState = -1;
		return;
	}

	$NumRows = mysqli_num_rows($result);

// debug
	$this->err_level = 16;
	$this->err = "CleanUp query 01 number of rows feched: " . (string)$NumRows;
	$this->err_no = 0;
	$this->DbgAddSqlLog();

	/* TODO: save stale sessions */

// delete stale sessions
	$query="delete from actsessions where lastused<$still_valid";

// debug
	$this->err_level = 16;
	$this->err = "CleanUp query 02: " . (string)$query;
	$this->err_no = 0;
	$this->DbgAddSqlLog();

	$result = mysqli_query($this->linkid,$query);

// debug
	$this->err_level = 16;
	$this->err = "CleanUp query 02 executed";
	$this->err_no = 0;
	$this->DbgAddSqlLog();

// query failed
	if (!$result) {
		$this->err_level = -1;
		$this->err=mysqli_error($this->linkid);
		$this->err_no=204;
		$this->DbgAddSqlLog();
		$this->AutomatState = -1;
		return;
	}

// debug
	if ($NumRows) {
		$this->err_level = 1;
		$this->err = "CleanUp: Stale sessions deleted #:" . $NumRows;
		$this->err_no = 0;
		$this->DbgAddSqlLog();
	}
}


// method to add log to SQL table:
// ------------------------------
function DbgAddSqlLog() {
if ($this->err_level <= $this->DebugLevel) {
	$DBGcurrent=time();
	$this->err=str_replace("'", " ", $this->err);
	$DBGquery="insert into debuglog values(";
	$DBGquery.="$DBGcurrent,'$this->sid','$this->uid', '$this->err_no',";
	$DBGquery.="'$this->err' )";
/* $DBGquery = addslashes($DBGquery); */
/* echo ("<br>" . $DBGquery . "<br>"); */
	$DBGresult=mysqli_query($this->linkid,$DBGquery);
	if (!$DBGresult) {
		$this->err=mysqli_error($this->linkid);
		$this->err_no=901;
		return;
	}
	$this->err_no=999;
}
	return;
}

// method to switch automat state:
// ------------------------------
function AutomatSwitchState() {

// Store current state to statefrom field
	$query  = "update actsessions ";
	$query .= "set statefrom=$this->AutomatState ";
	$query .= "where seshid='$this->seshid'";
// debug
	$this->err_level = 16;
	$this->err = "AutomatSwitchState. query: " . (string)$query;
	$this->err_no = 0;
	$this->DbgAddSqlLog();
	$result=mysqli_query($this->linkid,$query);
// query failed
	if (!$result) {
		$this->err_level = -1;
		$this->err=mysqli_error($this->linkid);
		$this->err_no=204;
		$this->DbgAddSqlLog();
		$this->AutomatState = -1;
		return;
	}

// Store new state to state field
	$query  = "update actsessions ";
	$query .= "set state=$this->NewAutomatState ";
	$query .= "where seshid='$this->seshid'";
// debug
	$this->err_level = 16;
	$this->err = "AutomatSwitchState. query: " . (string)$query;
	$this->err_no = 0;
	$this->DbgAddSqlLog();
	$result=mysqli_query($this->linkid,$query);
// query failed
	if (!$result) {
		$this->err_level = -1;
		$this->err=mysqli_error($this->linkid);
		$this->err_no=204;
		$this->DbgAddSqlLog();
		$this->AutomatState = -1;
		return;

	}
	$this->PreviousAutomatState = $this->AutomatState;
	$this->AutomatState = $this->NewAutomatState;
	return;
}

// destructor:
// ----------
function Destructor() {

// debug
	$this->err_level = 16;
	$this->err = "Destructor entered. ";
	$this->err_no = 0;
	$this->DbgAddSqlLog();

	unset($sqlhost);
	unset($sqluser);
	unset($sqlpass);
	unset($sqldb);
	unset($linkid);
	unset($sid);
	unset($seshid);
	unset($sessdata);
	unset($err);
	unset($err_no);
	unset($err_level);
	unset($expire_time);
	unset($uid);
	unset($AutomatState);
	unset($NewAutomatState);
	unset($PreviousAutomatState);
	unset($AutomatAction);
	unset($AutomatModul);
	unset($DebugLevel);
	unset($DebugMessage);
}

// end of class definition
}

?>
