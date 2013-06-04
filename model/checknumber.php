<?php
include_once("../phonebook.inc.php");
include_once("../../kint/Kint.class.php");
extract($_GET);

// return 0 for new user
// return 1 for password request
// return 2 for confirmation code request
// return 3 for login success
// return 4 for enter name
if(!empty($number)){
	$query_string = "SELECT * FROM users WHERE number = '$number' ";
	$q = mysql_query($query_string) or die($query_string);
	$result = mysql_fetch_assoc($q);
	if($result == false){ // not( registered
		if(isset($real_name)){ // name is sent
			$_SESSION['name'] = $real_name;
			registerUser();
			registerUserToContact();
			unset($real_name);
			echo "3";
		}
		if(isset($password)){ // if password is sent
			$hasedPass = md5($password);
			$_SESSION['password'] = $hasedPass;
			unset($password);
			echo "4";
		}else{
			if(isset($_SESSION['confirmCode']) == false) { // if the confirmation code is not sent
				// send out code
				$_SESSION['number'] = $number;
				$_SESSION['confirmCode'] = generateCode();
				sendText();
				echo "2";	// response confirmation code
			}else{ // check confirmation code
				if($code == $_SESSION['confirmCode']){ // if correct, send out password input request or register user
					// send out password input request
					echo "1";
				}else{ // if the confirmation code is not correct
					$_SESSION['confirmCode'] = generateCode();
					sendText();
					echo "2";
				}
			}
		}
	}else{ // registered 
		if(isset($password) && !empty($password)){ // step for checking password
			$hasedPass = md5($password);
			$query_string = "SELECT * FROM users WHERE number = '$number' AND password = '$hasedPass'";
			$q = mysql_query($query_string) or die($query_string);
			$result = mysql_fetch_assoc($q);
			if($result == false){ // password not match
				echo "1";
			}else{ // password match
				$_SESSION['ID'] = $result['ID'];
				$_SESSION['number'] = $number;
				echo "3";
			}
		}else{ // if just checking phone number
			echo "1";
		}
	} 

}

?>