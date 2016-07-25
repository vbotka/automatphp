<?php

$SMSEMAIL=1;
$DEBUGLEVELAUTH=6;

$hidden_hash_var='your_password_here';
$LOGGED_IN=false;
//clear it out in case someone sets it in the URL or something
unset($LOGGED_IN);

/*
  create table user (
  user_id int not null auto_increment primary key,
  user_name text,
  real_name text,
  email text,
  password text,
  remote_addr text,
  confirm_hash text,
  is_confirmed int not null default 0
  );
*/

function user_isloggedin() {
	global $user_name,$id_hash,$hidden_hash_var,$LOGGED_IN;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	//have we already run the hash checks? 
	//If so, return the pre-set var
	if (isset($LOGGED_IN)) {
		return $LOGGED_IN;
	}
	if ($user_name && $id_hash) {
		$hash=md5($user_name.$hidden_hash_var);
		if ($hash == $id_hash) {
			$LOGGED_IN=true;
			return true;
		} else {
			$LOGGED_IN=false;
			return false;
		}
	} else {
		$LOGGED_IN=false;
		return false;
	}
}

function user_login($user_name,$password) {
	global $feedback, $uid;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	if (!$user_name || !$password) {
		/* $feedback .=  ' ERROR - Missing user name or password '; */
		$feedback .=  ' CHYBA - Chybi jmeno nebo heslo ';
		return false;
	} else {
		$user_name=strtolower($user_name);
		$password=strtolower($password);
		$sql="SELECT * FROM user WHERE user_name='$user_name' AND password='". md5($password) ."'";
		$result=mysqli_query($mysesh->linkid,$sql);
		if (!$result || mysqli_num_rows($result) < 1){
			/* $feedback .=  ' ERROR - User not found or password incorrect '; */
			$feedback .=  ' CHYBA - Nenasel jsem jmeno, nebo heslo je nespravne ';
			return false;
		} else {
			$uid = mysqli_result($result,0,'user_id');
			if (mysqli_result($result,0,'is_confirmed') == '1') {
				user_set_tokens($user_name);
				/* $feedback .=  ' SUCCESS - You Are Now Logged In '; */
				$feedback .=  ' Uspesne jste se prihlasil. ';
				return true;
			} else {
				/* $feedback .=  ' ERROR - You haven\'t Confirmed Your Account Yet '; */
				$feedback .=  ' CHYBA - Jeste jste epotvrdili jste svoji registraci ';
				return false;
			}
		}
	}
}

function user_logout() {
    //	setcookie('user_name','',(time()+2592000),'/','',0);
    //	setcookie('id_hash','',(time()+2592000),'/','',0);
}

function user_set_tokens($user_name_in) {
	global $hidden_hash_var,$user_name,$id_hash;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	if (!$user_name_in) {
		/* $feedback .=  ' ERROR - User Name Missing When Setting Tokens '; */
		$feedback .=  ' CHYBA - Chybi jmeno, kdyz nastavuji tokeny ';
		return false;
	}
	$user_name=strtolower($user_name_in);
	$id_hash= md5($user_name.$hidden_hash_var);

    //	setcookie('user_name',$user_name,(time()+2592000),'/','',0);
    //	setcookie('id_hash',$id_hash,(time()+2592000),'/','',0);
}

/* function user_confirm($hash,$email) {
// Call this function on the user confirmation page,
// which they arrive at when the click the link in the
// account confirmation email
global $feedback,$hidden_hash_var;
global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
// debug
$mysesh->err_level = $DEBUGLEVELAUTH;
$mysesh->err  = "user_confirm entered. email: ";
$mysesh->err .= (string)$email;
$mysesh->err .= " hash: " . $hash;
$mysesh->err .= " uid: " . $mysesh->uid;
$mysesh->err_no = 0;
$mysesh->DbgAddSqlLog();
//verify that they didn't tamper with the email address
$new_hash=md5($email.$hidden_hash_var);
if ($new_hash && ($new_hash==$hash)) {
//find this record in the db
$sql="SELECT * FROM user WHERE (confirm_hash='$hash') AND (user_id='$mysesh->uid')";
$result=mysqli_query($mysesh->linkid,$sql);
if (!$result || mysqli_num_rows($result) < 1) {
$feedback .= ' ERROR - Hash and-or Uid Not Found ';
$feedback .= ' CHYBA - Nenasel jsem hash a-nebo Uid ';
return false;
} else {
//confirm the email and set account to active
$feedback .= ' User Account Updated - You Are Now Logged In ';
user_set_tokens(mysqli_result($result,0,'user_name'));
$sql="UPDATE user SET email='$email',is_confirmed='1' WHERE confirm_hash='$hash'";
$result=mysqli_query($mysesh->linkid,$sql);
return true;
}
} else {
$feedback .= ' HASH INVALID - UPDATE FAILED ';
return false;
}
}
*/
function user_confirm($ConfirmHash) {
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
    // debug
	$mysesh->AutomatModul = "user_confirm";
	$mysesh->err_level = $DEBUGLEVELAUTH;
	$mysesh->err  = (string)$mysesh->AutomatModul;
	$mysesh->err .= " entered. ConfirmHash: " . $mysesh->ConfirmHash;
	$mysesh->err .= " Typed: " . $ConfirmHash;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();
    // clear confirmjpeg
	if ($ConfirmHash == $mysesh->ConfirmHash) {
		$sql="update actsessions set confirmjpeg=NULL,uvalid='1' where seshid='$mysesh->seshid'";
		$result=mysqli_query($mysesh->linkid,$sql);
		if (!$result) {
			$mysesh->err_level = $DEBUGLEVELERROR;
			$mysesh->err  = mysqli_error($mysesh->linkid);
			$mysesh->err_no = 999;
			$mysesh->DbgAddSqlLog();
		} else {
			$mysesh->err_level = $DEBUGLEVELAUTH;
			$mysesh->err  = "user_confirm() session validated. seshid: " . $mysesh->seshid;
			$mysesh->err .= (string)$user_id;
			$mysesh->err_no = 0;
			$mysesh->DbgAddSqlLog();
		}
        // clear confirm_hash
		$sql="update user set confirm_hash=NULL,is_confirmed='1' where user_id='$mysesh->uid'";
		$result=mysqli_query($mysesh->linkid,$sql);
		if (!$result) {
			$mysesh->err_level = $DEBUGLEVELERROR;
			$mysesh->err  = mysqli_error($mysesh->linkid);
			$mysesh->err_no = 999;
			$mysesh->DbgAddSqlLog();
		} else {
			$mysesh->err_level = $DEBUGLEVELAUTH;
			$mysesh->err  = "user_confirm() user validated. user_id: " . $mysesh->uid;
			$mysesh->err .= (string)$user_id;
			$mysesh->err_no = 0;
			$mysesh->DbgAddSqlLog();
		}
	}
	return;
}

function user_confirm_email($ConfirmHash) {
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
    // debug
	$mysesh->AutomatModul = "user_confirm_email";
	$mysesh->err_level = $DEBUGLEVELAUTH;
	$mysesh->err  = (string)$mysesh->AutomatModul;
	$mysesh->err .= " entered. ConfirmHash: " . $mysesh->ConfirmHash;
	$mysesh->err .= " Typed: " . $ConfirmHash;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();
    // clear confirmjpeg
	if ($ConfirmHash == $mysesh->ConfirmHash) {
        // clear confirm_hash, switch email
		$sql="update user set email=new_email,new_email=NULL,confirm_hash=NULL where user_id='$mysesh->uid'";
		$result=mysqli_query($mysesh->linkid,$sql);
		if (!$result) {
			$mysesh->err_level = $DEBUGLEVELERROR;
			$mysesh->err  = mysqli_error($mysesh->linkid);
			$mysesh->err_no = 999;
			$mysesh->DbgAddSqlLog();
		} else {
			$mysesh->err_level = $DEBUGLEVELAUTH;
			$mysesh->err  = "user_confirm_email() email changed. user_id: " . $mysesh->uid;
			$mysesh->err .= (string)$user_id;
			$mysesh->err_no = 0;
			$mysesh->DbgAddSqlLog();
		}
	}
	return;
}

function user_change_password ($new_password1,$new_password2,$change_user_name,$old_password) {
	global $feedback;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	//new passwords present and match?
	if ($new_password1 && ($new_password1==$new_password2)) {
		//is this password long enough?
		if (account_pwvalid($new_password1)) {
			//all vars are present?
			if ($change_user_name && $old_password) {
				//lower case everything
				$change_user_name=strtolower($change_user_name);
				$old_password=strtolower($old_password);
				$new_password1=strtolower($new_password1);
				$sql="SELECT * FROM user WHERE user_name='$change_user_name' AND password='". md5($old_password) ."'";
				$result=mysqli_query($mysesh->linkid,$sql);
				if (!$result || mysqli_num_rows($result) < 1) {
					// $feedback .= ' User not found or bad password '.mysqli_error($mysesh->linkid);
					$feedback .= ' Jmeno nenalezeno, nebo spatne heslo '.mysqli_error($mysesh->linkid);
					return false;
				} else {
					$sql="UPDATE user SET password='". md5($new_password1). "' ".
						"WHERE user_name='$change_user_name' AND password='". md5($old_password). "'";
					$result=mysqli_query($mysesh->linkid,$sql);
					if (!$result || mysqli_affected_rows($mysesh->linkid) < 1) {
						// $feedback .= ' NOTHING Changed '.mysqli_error($mysesh->linkid);
						$feedback .= ' CHYBA heslo se nezmenilo '.mysqli_error($mysesh->linkid);
						return false;
					} else {
						// $feedback .= ' Password Changed ';
						$feedback .= ' Heslo se zmenilo. ';
						return true;
					}
				}
			} else {
				// $feedback .= ' Must Provide User Name And Old Password ';
				$feedback .= ' Musite zadat jmeno pro prihlaseni a stare heslo ';
				return false;
			}
		} else {
			// $feedback .= ' New Passwords Doesn\'t Meet Criteria ';
			$feedback .= ' Nove heslo musi byt min. 6 znaku dlouhe ';
			return false;
		}
	} else {
		$feedback .= ' Nezadal jste 2x stejne nove heslo ';
		return false;
		// $feedback .= ' New Passwords Must Match ';
	}
	$feedback .= ' Nezadal jste jmeno ';
	return false;
}

function user_lost_password ($email,$user_name) {
	global $feedback,$hidden_hash_var;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	if ($email && $user_name) {
		$user_name=strtolower($user_name);
		$sql="SELECT * FROM user WHERE user_name='$user_name' AND email='$email'";
		$result=mysqli_query($mysesh->linkid,$sql);
		if (!$result || mysqli_num_rows($result) < 1) {
			//no matching user found
			/* $feedback .= ' ERROR - Incorrect User Name Or Email Address '; */
			$feedback .= ' CHYBA - Neplatne jmeno nebo email ';
			return false;
		} else {
			//create a secure, new password
			$new_pass=strtolower(substr(md5(time().$user_name.$hidden_hash_var),1,14));

			//update the database to include the new password
			$sql="UPDATE user SET password='". md5($new_pass) ."' WHERE user_name='$user_name'";
			$result=mysqli_query($mysesh->linkid,$sql);

			//send a simple email with the new password
			// mail ($email,'Password Reset','Your Password has been reset to: '.$new_pass,'From: noreply@company.com');
			mail ($email,'Password Reset','Vase nove heslo: '.$new_pass,'From: noreply@objednavky.com');
			// $feedback .= ' Your new password has been emailed to you. ';
			$feedback .= ' Nove heslo Vam bylo zaslano na email. ';
			return true;
		}
	} else {
		/* $feedback .= ' ERROR - User Name and Email Address Are Required '; */
		$feedback .= ' CHYBA - Jmeno a email je povinne ';
		return false;
	}
}

function user_change_email ($password1,$new_email,$user_name) {
	global $feedback,$hidden_hash_var;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
    // debug
	$mysesh->err_level = $DEBUGLEVELAUTH;
	$mysesh->err  = "user_change_email entered. user: ";
	$mysesh->err .= (string)$user_name;
	$mysesh->err .= " new_email: " . $new_email;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();
    //
	if (validate_email($new_email)) {
		if ($SMSEMAIL) {
			$hash=substr(md5($new_email.$hidden_hash_var.time()),1,6);
		} else {
			$hash=md5($new_email.$hidden_hash_var);
		}
		//change the confirm hash in the db but not the email - 
		//send out a new confirm email with a new hash
		$user_name=strtolower($user_name);
		$password1=strtolower($password1);
		$sql="UPDATE user SET new_email='$new_email',confirm_hash='$hash' WHERE user_name='$user_name' AND password='". md5($password1) ."'";
		/* echo ("<br>" . $sql . "<br>"); */
		$result=mysqli_query($mysesh->linkid,$sql);
		if (!$result || mysqli_affected_rows() < 1) {
			/* $feedback .= ' ERROR - Incorrect User Name Or Password '; */
			$feedback .= ' CHYBA - Neplatne jmeno nebo heslo. ';
			return false;
		} else {
			/* $feedback .= ' Confirmation Sent '; */
			$feedback .= ' Heslo pro potvrzeni odeslane. ';
			user_send_confirm_email($new_email,$hash);
			return true;
		}
	} else {
		$feedback .= ' New Email Address Appears Invalid ';
		return false;
	}
}

function user_send_confirm_email($email,$hash) {
	/*
      Used in the initial registration function
      as well as the change email address function
	*/
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
    // debug
	$mysesh->err_level = $DEBUGLEVELAUTH;
	$mysesh->err  = "user_send_confirm_email entered. email: ";
	$mysesh->err .= (string)$email;
	$mysesh->err .= " hash: " . $hash;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();
    //
	if ($SMSEMAIL) {
		$message = $hash;
		mail ($email,' ',$message,'From: noreply@objednavky.com');
	} else {
		$message = "Thank You For Registering at PHPBuilder.com".
                 "\nSimply follow this link to confirm your registration: ".
                 "\n\nhttp://www.phpbuilder.com/account/confirm.php?hash=$hash&email=". urlencode($email).
                 "\n\nOnce you confirm, you can use the services on PHPBuilder.";
		mail ($email,'PHPBuilder Registration Confirmation',$message,'From: noreply@phpbuilder.com');
	}
}

function user_register_check($user_name,$password1,$password2,$email,$real_name) {
	global $feedbackErrUserName, $feedbackErrPassword1, $feedbackErrPassword2, $feedbackErrEmail;
	global $feedbackErrUserNameMsg, $feedbackErrPassword1Msg, $feedbackErrPassword2Msg, $feedbackErrEmailMsg;
    //
	$feedbackErrUserName = 0;
	$feedbackErrPassword1 = 0;
	$feedbackErrPassword2 = 0;
	$feedbackErrPasswordMatch = 0;
	$feedbackErrEmail = 0;
    //
	if (!$user_name) {
		$feedbackErrUserNameMsg = 'nevyplnene';
		$feedbackErrUserName = 1;
	} else {
		if (strrpos($user_name,' ') > 0) {
			$feedbackErrUserNameMsg = 'obsahuje mezery<br>';
			$feedbackErrUserName = 1;
		}
		if (strspn($user_name,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") == 0) {
			$feedbackErrUserNameMsg .= 'neobsahuje pismeno<br>';
			$feedbackErrUserName = 1;
		}
		if (strspn($user_name,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_") != strlen($user_name)) {
			$feedbackErrUserNameMsg .= 'obsahuje nepovoleny znak<br>';
			$feedbackErrUserName = 1;
		}
		if (strlen($user_name) < 5) {
			$feedbackErrUserNameMsg .= 'myusi byt min. 5 znaku<br>';
			$feedbackErrUserName = 1;
		}
		if (strlen($user_name) > 15) {
			$feedbackErrUserNameMsg .= 'myusi byt max. 15 znaku<br>';
			$feedbackErrUserName = 1;
		}
		if ((eregi("^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)"
                   . "|(uucp)|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)"
                   . "|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$",$user_name)) || (eregi("^(anoncvs_)",$name))) {
			$feedbackErrUserNameMsg .= 'jmeno je rezervovane <br>';
			$feedbackErrUserName = 1;
		}
	}
	if (!$password1) {
		$feedbackErrPassword1Msg = 'nevyplnene';
		$feedbackErrPassword1 = 1;
	} else {
		if (strlen($password1) < 6) {
			$feedbackErrPassword1Msg = 'min. 6 znaku<br>';
			$feedbackErrPassword1 = 1;
		}
	}
	if (!$password2) {
		$feedbackErrPassword2Msg = 'nevyplnene';
		$feedbackErrPassword2 = 1;
	} else {
		if (!($password1==$password2)) {
			$feedbackErrPassword2Msg = 'hesla nejsou stejna';
			$feedbackErrPassword2 = 1;
		}
	}
	if (!(validate_email($email))) {
		$feedbackErrEmailMsg = 'neplatny email';
		$feedbackErrEmail = 1;
	}
	return;
}

function user_register($user_name,$password1,$password2,$email,$real_name) {
	global $feedback,$hidden_hash_var;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH; 
    // debug
	$mysesh->err_level = $DEBUGLEVELAUTH;
	$mysesh->err  = "user_register entered. user: ";
	$mysesh->err .= (string)$user_name;
	$mysesh->err .= " email: " . $email;
	$mysesh->err_no = 0;
	$mysesh->DbgAddSqlLog();

    //all vars present and passwords match?
	if ($user_name && $password1 && $password1==$password2 && $email && validate_email($email)) {
		//password and name are valid?
		if (account_namevalid($user_name) && account_pwvalid($password1)) {
			$user_name=strtolower($user_name);
			$password1=strtolower($password1);
			//does the name exist in the database?
			$sql="SELECT * FROM user WHERE user_name='$user_name'";
			$result=mysqli_query($mysesh->linkid,$sql);
			if ($result && mysqli_num_rows($result) > 0) {
				/* $feedback .=  ' ERROR - USER NAME EXISTS '; */
				$feedback .=  ' CHYBA - jmeno pro prihlaseni uz existuje ';
				return false;
			} else {
				//create a new hash to insert into the db and the confirmation email
				if ($SMSEMAIL) {
					$hash=substr(md5($email.$hidden_hash_var.time()),1,6);
				} else {
					$hash=md5($email.$hidden_hash_var.time());
				}
				$sql="INSERT INTO user (user_name,real_name,password,email,remote_addr,confirm_hash,is_confirmed) ".
					"VALUES ('$user_name','$real_name','". md5($password1) ."','$email','$GLOBALS[REMOTE_ADDR]','$hash','0')";
				$result=mysqli_query($mysesh->linkid,$sql);
				if (!$result) {
					$feedback .= ' ERROR - '.mysqli_error($mysesh->linkid);
					return false;
				} else {
					//send the confirm email
					user_send_confirm_email($email,$hash);
					$feedback .= ' Successfully Registered. You Should Have a Confirmation Email Waiting ';
                    // update actsession table uid
					$sql="select user_id from user where user_name='$user_name'";
					$result=mysqli_query($mysesh->linkid,$sql);
					if (!$result) {
						$feedback .= ' ERROR - '.mysqli_error($mysesh->linkid);
						return false;
					} else {
						$user_id = mysqli_result($result,0,'user_id');
						$sql="update actsessions set uid='$user_id' where seshid='$mysesh->seshid'";
						$result=mysqli_query($mysesh->linkid,$sql);
						if (!$result) {
							$feedback .= ' ERROR - '.mysqli_error($mysesh->linkid);
							return false;
						}
                        // debug
						$mysesh->err_level = $DEBUGLEVELAUTH;
						$mysesh->err  = "user_register updated actsession table. uid: ";
						$mysesh->err .= (string)$user_id;
						$mysesh->err_no = 0;
						$mysesh->DbgAddSqlLog();
						return true;
					}
				}
			}
		} else {
			$feedback .=  ' Account Name or Password Invalid ';
			return false;
		}
	} else {
		$feedback .=  ' ERROR - Must Fill In User Name, Matching Passwords, And Provide Valid Email Address ';
		return false;
	}
}

function user_getid() {
	global $G_USER_RESULT;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT) {
		$G_USER_RESULT=mysqli_query($mysesh->linkid,"SELECT * FROM user WHERE user_name='" . user_getname() . "'");
	}
	if ($G_USER_RESULT && mysqli_num_rows($G_USER_RESULT) > 0) {
		return mysqli_result($G_USER_RESULT,0,'user_id');
	} else {
		return false;
	}
}

function user_getrealname() {
	global $G_USER_RESULT;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT) {
		$G_USER_RESULT=mysqli_query($mysesh->linkid,"SELECT * FROM user WHERE user_name='" . user_getname() . "'");
	}
	if ($G_USER_RESULT && mysqli_num_rows($G_USER_RESULT) > 0) {
		return mysqli_result($G_USER_RESULT,0,'real_name');
	} else {
		return false;
	}
}

function user_getemail() {
	global $G_USER_RESULT;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT) {
		$G_USER_RESULT=mysqli_query($mysesh->linkid,"SELECT * FROM user WHERE user_name='" . user_getname() . "'");
	}
	if ($G_USER_RESULT && mysqli_num_rows($G_USER_RESULT) > 0) {
		return mysqli_result($G_USER_RESULT,0,'email');
	} else {
		return false;
	}
}

function user_getname() {
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	if (user_isloggedin()) {
		return $GLOBALS['user_name'];
	} else {
		//look up the user some day when we need it
		/* return ' ERROR - Not Logged In '; */
		return ' CHYBA - Neprihlasen ';
	}
}

function account_pwvalid($pw) {
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	global $feedback;
	global $feedbackErrPassword1;
	if (strlen($pw) < 6) {
		$feedback .= " Password must be at least 6 characters. ";
		$feedbackErrPassword1 = 1;
		return false;
	}
	return true;
}

function account_namevalid($name) {
	global $feedback;
	global $mysesh, $SMSEMAIL, $DEBUGLEVELAUTH;
	global $feedbackErrUserName;
	// no spaces
	if (strrpos($name,' ') > 0) {
		$feedback .= " There cannot be any spaces in the login name. ";
		$feedbackErrUserName = 1;
		return false;
	}

	// must have at least one character
	if (strspn($name,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") == 0) {
		$feedback .= "There must be at least one character.";
		$feedbackErrUserName = 1;
		return false;
	}

	// must contain all legal characters
	if (strspn($name,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_")
		!= strlen($name)) {
		$feedback .= " Illegal character in name. ";
		$feedbackErrUserName = 1;
		return false;
	}

	// min and max length
	if (strlen($name) < 5) {
		$feedback .= " Name is too short. It must be at least 5 characters. ";
		$feedbackErrUserName = 1;
		return false;
	}
	if (strlen($name) > 15) {
		$feedback .= "Name is too long. It must be less than 15 characters.";
		$feedbackErrUserName = 1;
		return false;
	}

	// illegal names
	if (eregi("^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)"
              . "|(uucp)|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)"
              . "|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$",$name)) {
		$feedback .= "Name is reserved.";
		$feedbackErrUserName = 1;
		return 0;
	}
	if (eregi("^(anoncvs_)",$name)) {
		$feedback .= "Name is reserved for CVS.";
		$feedbackErrUserName = 1;
		return false;
	}

	return true;
}

function validate_email ($address) {
	return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address));
}

?>
