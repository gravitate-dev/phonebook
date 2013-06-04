<?php 
session_start();
if (!($dbhandle = @mysql_connect ("localhost", "aaaaaaaaaaaaaaaaaaaaaaaaaaaa", "aaaaaaaaaaaaaaaaaaaaaaaaaaa")))
{
	exit ("<body><b>Fatal Error</b>: Could not connect to MySQL host...</body>");
}
if (!(@mysql_select_db ("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")))
{
	exit ("<body><b>Fatal Error</b>: Could not select MySQL database...</body>"); 
}


function Login($number, $password){

$query_string= "SELECT COUNT * FROM users WHERE number='$number' AND password='$password'";
$q = mysql_query($query_string);
$result = mysql_fetch_assoc($q);
return $result;
}
/**
Function for get data from database
**/
function getData(){

}

/**
Fucntion for send out text
**/
function sendText() {
	$number = mysql_real_escape_string($_SESSION['number']);
	$code = mysql_real_escape_string($_SESSION['confirmCode']);
	
	$message = "Your confirmation code is " .$code .".";
	
	if($number == "911"){
	}else{
		mail($number."@tmomail.net","Text from Phonebook",$message);
		mail($number."@text.att.net", "Text from Phonebook", $message);
		mail($number."@vtext.com", "Text from Phonebook", $message);
	}
}

/**
Function to generate random code for user
**/
function generateCode () {
return strval((floor(microtime(true))%100000));
}


/** 
Function for register user
**/
function registerUser(){
$number = mysql_real_escape_string($_SESSION['number']);
$code = mysql_real_escape_string($_SESSION['confirmCode']);
$hasedPass = mysql_real_escape_string($_SESSION['password']);
$name = mysql_real_escape_string($_SESSION['name']);
$query_string = "INSERT INTO users (ID, number, password, confirmCode, name) VALUES (NULL, '$number', '$hasedPass', $code, '$name')";
mysql_query($query_string) or die($query_string);
}
/** 
Function for register user itself on the contact list
**/
function registerUserToContact(){
$number = mysql_real_escape_string($_SESSION['number']);
$name = mysql_real_escape_string($_SESSION['name']);
$query_string = "SELECT ID FROM users WHERE number = '$number'";
$q = mysql_query($query_string) or die($query_string);
$result = mysql_fetch_assoc($q);
$ID = $result['ID'];
$query_string = " INSERT INTO user_contact (ID, name, contact) VALUES ($ID, '$name', '$number')";
mysql_query($query_string) or die($query_string);
}

/**
Function for get data of users
**/
function getContacts($number){
$query_string = "SELECT user_contact.name, user_contact.contact FROM user_contact JOIN users ON (users.ID = user_contact.ID) WHERE users.number = '$number'";
$q = mysql_query($query_string) or die($query_string);
$ans = array();
while($result = mysql_fetch_assoc($q)){
array_push($ans, $result);
}
return $ans;
}

/**
Fucntion for send out text
**/
function sendTextContact($number, $text, $name) {
	$message = $text;

	if( $number == "911" || 
		empty($text)){
	}else{
		mail($number."@tmomail.net","Text from Phonebook by " .$name, $message);
		mail($number."@text.att.net", "Text from Phonebook by " .$name, $message);
		mail($number."@vtext.com", "Text from Phonebook by " .$name, $message);
		echo "Sent text message to ".$number .".";
	}
}

/**
Function for counting the total number of contacts
**/
function getNumberOfContact(){
$ID = intval($_SESSION['ID']);
$query_string = "SELECT COUNT(ID) FROM user_contact WHERE ID = $ID";
$q = mysql_query($query_string) or die($query_string);
$result = mysql_fetch_assoc($q);
return $result['COUNT(ID)'];
}
?>