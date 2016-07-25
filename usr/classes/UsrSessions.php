<?php

// example from "Professional PHP Programming" ISBN 80-7226-310-2
// TODO:
//   - add log to file for MySQL errors
//   - scramble password to MySQL and store it in the file outside the web tree

// Define the UsrSession class
class UsrSession {

    // Define the properties:
	var $sqlhost = "localhost";
	var $sqluser = "automatphp";
	var $sqlpass = "passwd";
	var $sqldb = "automatphp";
	var $linkid;

    // session
	var $sid;
	var $seshid;
	var $sessdata;
	var $err;
	var $err_no;
	var $err_level;
	var $expire_time = 900; // length of time until expiration in seconds

    // user, group, customers, remotehost
	var $uid;
	var $uname;
	var $user;
	var $UserValid;
	var $gid;
	var $group;
	var $RemoteIP;
	var $RemoteHost;
	var $ActiveCid;

    // registration
	var $ConfirmJpeg;
	var $ConfirmHash;

    // automat
	var $AutomatState;
	var $NewAutomatState;
	var $PreviousAutomatState;
	var $AutomatAction;
	var $AutomatModul;
	var $DebugLevel;
	var $DebugMessage;
	var $AutomatData;
	var $AutomatDataRows;
	var $AutomatDataFields;

    // colors
	var $PageBgColor = "\"#ffe7ba\"";
	var $PageBgColor1 = "\"#215e5e\"";
	var $PageTextColor = "\"#000000\"";
	var $PageLinkColor = "\"#E9967A\"";		// dark salmon
	var $PageVisitedLinkColor = "\"#ffa07a\"";	// light salmon
	var $PageActiveLinkColor = "\"#fa8072\"";	// salmon
	var $ColorRed = "\"#7A2829\"";			// 
	var $ColorYellow = "\"#D9E312\"";

    // menu
	var $MenuItemSelected;
	var $MenuItemSelectedColor = "\"#7D4E4F\"";
	var $MenuItemColor = "\"#2B7D50\"";

    // constructor
    function UsrSession($localSeshID,$localCid) {
        $this->seshid = $localSeshID;
        $this->ActiveCid = $localCid;
        // $this->uid = $localUserID;
        // connect to MySQL
        $this->linkid=mysqli_connect($this->sqlhost,$this->sqluser,$this->sqlpass);
        // echo ( "<br> function mysqli_connect returned <br>" );
        // verify connection made
        if (!$this->linkid) {
            $this->err_level = -1;
            $this->err = mysqli_error($this->linkid);
            $this->err_no = 101;
            $this->AutomatState = -1;
            return;
        }
        // echo ( "<br> DB connected <br>" );
        // select database
        $result=mysqli_select_db($this->linkid,$this->sqldb);
        // unable to select db
        if (!$result) {
            $this->err_level = -1;
            $this->err=mysqli_error($this->linkid);
            $this->err_no=102;
            $this->AutomatState = -1;
            return;
        }
        // echo ( "<br> DB selected <br>" );
        // check to see if verifying session or creating session
        // not new session, verify
        // echo ( "<br> Select from actsessions entering <br>" );
        if($this->seshid) {
            $result = mysqli_query($this->linkid,"SELECT * FROM actsessions WHERE seshid='$this->seshid'");
            // echo ( "<br> Select from actsessions returned <br>" );
            // select failed
            if (!$result) {
                $this->err_level = -1;
                $this->err=mysqli_error($this->linkid);
                $this->err_no=103;
                $this->AutomatState = -1;
                return;
            }
            // echo ( "<br> Select from actsessions returned <br>" );
            // verify valid data returned
            $numrows=mysqli_num_rows($result);
            // no rows returned, seshid not valid, maybe expired and cleaned, setting seshid=0, uid=2
            if (!$numrows) {
                $this->err_level = -1;
                $this->err="Session id not valid. seshid: " . (string)$this->seshid;
                $this->err_no=201;
                $this->DbgAddSqlLog();
                $this->seshid = 0;
                $this->uid = 2;
            }
            // get session data
            else {
                $this->sessdata = mysqli_fetch_array($result);
                // restore returning session state
                $this->AutomatState = $this->sessdata["state"];
                $this->PreviousAutomatState = $this->sessdata["statefrom"];
                $this->DebugLevel = $this->sessdata["dbglevel"];
                $this->uid = $this->sessdata["uid"];
                $this->sid = $this->sessdata["sid"];
                $this->ConfirmJpeg = $this->sessdata["confirmjpeg"];
                $this->ActiveCid = $this->sessdata["cid"];
                // TODO: Increase security, by checking IP each time the session returns
                $this->RemoteIP = $this->sessdata["remoteip"];
                $this->RemoteHost = $this->sessdata["remotehost"];
                // check if the session expired
                $not_expired = $this->VerifyTime();
                if (!$not_expired) {
                    // session expired, setting seshid=0
                    $current = time();
                    $lastused = $this->sessdata["lastused"];
                    $this->err_level = -1;
                    $this->err="Session has expired. lastUsed: $lastused expireTime: $this->expire_time currTime: $current";
                    $this->err_no=202;
                    $this->DbgAddSqlLog();
                    $this->seshid = 0;
                }
                // TODO: Increase security, we can change seshid each time the session returns
            }
        }
        // uid handling
        // if not valid session, uid was set to 2 (nobody)
        // if session expired, uid was set from actsessions
        // admin cat never log into the user automat
        // echo ( "<br> UID handling started <br>" );
        if ( $this->uid < 2 )
            $this->uid = 2;
        // get user data
        $result = mysqli_query($this->linkid,"SELECT * FROM user WHERE user_id='$this->uid'");
        // select failed, not valid user
		if (!$result) {
			$this->err_level = -1;
			$this->err=mysqli_error($this->linkid);
			$this->err_no=103;
			$this->AutomatState = -1;
			return;
		}
        // echo ( "<br> UID selected: " . $this->uid . " <br>" );
        // verify valid data returned
		$numrows=mysqli_num_rows($result);
        // no rows returned, not valid user
		if (!$numrows) {
			$this->err_level = -1;
			$this->err="User id not valid. uid: " . (string)$this->uid;
			$this->err_no=201;
			$this->DbgAddSqlLog();
			$this->AutomatState = -1;
			return;
		}
        // get user data
		else {
			$this->sessdata = mysqli_fetch_array($result);
			$this->user = $this->sessdata["real_name"];
			$this->uname = $this->sessdata["user_name"];
			$this->ConfirmHash = $this->sessdata["confirm_hash"];
		}
        // echo ( "<br> UID validated: " . $this->uname . " <br>" );
        // seshid 0 so creating
        if(!$this->seshid) {
            // echo ( "<br> Creating seshid <br>" );
            $current = time();
            $random = $this->uid . $current;
            $this->sid = $current;
            $this->seshid = md5($random);
            // setting default values ( table defaults to 0,0,0)
            $this->AutomatState = 0;
            $this->PreviousAutomatState = 0;
            $this->DebugLevel = 8;
            // user connected from
            $this->RemoteIP = $_SERVER["REMOTE_ADDR"];
            //		$this->RemoteIP = getenv ("REMOTE_ADDR");
            $this->RemoteHost = gethostbyaddr ($this->RemoteIP);
            // user validated
            //		$this->UserValid = 0;
            //
            $this->ActiveCid =0;
            $query  = "insert into actsessions ";
            $query .= "( sid, seshid, uid, lastused, dbglevel, remoteip, remotehost, cid) ";
            $query .= "values('$this->sid', '$this->seshid','$this->uid', $current, $this->DebugLevel,";
            $query .= " '$this->RemoteIP', '$this->RemoteHost', '$this->ActiveCid')";
            // echo ( "<br> Query: " . $query . " <br>" );
            $result=mysqli_query($this->linkid,$query);
            if (!$result) {
                $this->err_level = -1;
                $this->err = mysqli_error($this->linkid);
                $this->err_no = 104;
                $this->AutomatState = -1;
                // echo ( "<br> User session error: " . $this->err . " <br>" );
                return;
            }
            // echo ( "<br> User session created: " . $this->seshid . " <br>" );
            // finished session create return to script
            $this->err_level = 1;
            $this->err_no = 0;
            $this->err = "New session created. seshid: " . (string)$this->seshid;
            $this->DbgAddSqlLog();
            // user connected from
            $this->RemoteIP = $_SERVER["REMOTE_ADDR"];
            //		$this->RemoteIP = getenv ("REMOTE_ADDR");
            $this->RemoteHost = gethostbyaddr ($this->RemoteIP);
            // get automat data
            return;
        }
        // reset lastused to current
        $this->ResetTime("UsrSesions.php");
        // remove expired sessions
        $this->CleanUp();
        // debug
        $this->err_level = 16;
        $this->err = "Session returned. State: " . (string)$this->AutomatState . " Previous: " . (string)$this->PreviousAutomatState;
        $this->err_no = 0;
        $this->DbgAddSqlLog();
    }

    // method to verify if the session expired
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

    // method to reset session clock counter
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

    // method to clean stale sessions
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
    function DbgAddSqlLog() {
        if ($this->err_level <= $this->DebugLevel) {
            $DBGcurrent=time();
            $DBGquery="insert into debuglog values($DBGcurrent,'$this->sid','$this->uid', '$this->err_no',' $this->err' )";
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


    // method to fetch all automat data from usrautomat table:
    function AutomatFetchAll() {
        $query  = "select * from usrautomat";
        // debug
        $this->err_level = 16;
        $this->err = "AutomatFetchAll. query: " . (string)$query;
        $this->err_no = 0;
        $this->DbgAddSqlLog();
        $this->AutomatData = mysqli_query($this->linkid,$query);
        // query failed
        if (!$this->AutomatData) {
            $this->err_level = -1;
            $this->err=mysqli_error($this->linkid);
            $this->err_no=209;
            $this->DbgAddSqlLog();
            $this->AutomatState = -1;
            return;
        }
        $this->AutomatDataRows = mysqli_num_rows($this->AutomatData);
        $this->AutomatDataFields = mysqli_num_fields($this->AutomatData);
        if (!$this->AutomatDataRows) {
            // no data for this automat state
            $this->err_level = -1;
            $this->err=mysqli_error($this->linkid);
            $this->err_no=210;
            $this->DbgAddSqlLog();
            $this->AutomatState = -1;
            return;
        }
        return;
    }

    // method to fetch automat state data from usrautomat table:
    function AutomatFetchState($menu) {
        // $menu < 0 fetch menu data
        // $menu = 0 fetch all state data
        // $menu > 0 fetch only matching previou,action row
        $query  = "select * from usrautomat";
        // echo ( "<br> Select from usrautomat returned. Menu: " . $menu . " <br>" );
        if ($menu < 0) {
            $query .= " where previous='$this->AutomatState'";
            $query .= " and menu > '0'";
        } else if ($menu > 0) {
            $query .= " where previous='$this->AutomatState'";
            $query .= " and action = $menu";
            // TODO: there should be only 1 line, check consistency
        } else {
            $query .= " where previous='$this->AutomatState'";
        }
        // echo ( "Query: " . $query . " <br>" );
        // debug
        $this->err_level = 16;
        $this->err = "AutomatFetchState. query: " . (string)$query;
        $this->err_no = 0;
        $this->DbgAddSqlLog();
        // echo ( "<br> Query entering <br>" );
        $this->AutomatData = mysqli_query($this->linkid,$query);
        // echo ( "<br> Query returned <br>" );
        // query failed
        if (!$this->AutomatData) {
            $this->err_level = -1;
            $this->err=mysqli_error($this->linkid);
            $this->err_no=207;
            $this->DbgAddSqlLog();
            $this->AutomatState = -1;
            echo ( "<br> Query failed: " . $this->err . " <br>" );
            return;
        }
        $this->AutomatDataRows = mysqli_num_rows($this->AutomatData);
        $this->AutomatDataFields = mysqli_num_fields($this->AutomatData);
        if (!$this->AutomatDataRows) {
            // no data for this automat state
            $this->err_level = -1;
            $this->err=mysqli_error($this->linkid);
            $this->err_no=208;
            $this->DbgAddSqlLog();
            $this->AutomatState = -1;
            return;
        }
        return;
    }
    
    // destructor
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
