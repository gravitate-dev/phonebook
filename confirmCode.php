<?php

sendCode();
function generateCode () {
return strval((floor(microtime(true))%100000));
}

function sendCode($number) {

	$code= generateCode();
	echo $code;

	mail($number."@tmomail.net","Confirmation Code",$code);
	mail($number."@text.att.net", "Confirmation Code", $code);
	mail($number."@vtext.net", "Confirmation Code", $code);
	
	//update column confirmCode to equal $code in users table
}

function checkCode($number, $code){
//mysql query to check if $number and $code match what is in the users table
//if they do, erase what is in confirmCode in users table
}

?>