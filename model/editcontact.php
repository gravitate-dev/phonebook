<?php
session_start();
include_once("../phonebook.inc.php");
include_once("../../kint/Kint.class.php");

extract($_GET); // name, number, option(add, edit, delete)

$ID = $_SESSION['ID'];

if(strlen($new_name) > 32){
	// if name that want to be added is longer than 32 characters
	echo "1";
}else if(is_numeric($new_number) == FALSE){
	// if the new phone number is not a number!
	echo "1";
}else{
 // if name and number are okay to use!
	if($option == "add"){ // if user wants to add contact
		$query_string = "INSERT INTO user_contact (ID, name, contact) VALUES ($ID, '$new_name', '$new_number')";
		mysql_query($query_string) or die($query_string);
	}else if($option == "edit"){ // if user wants to edit contact
		$query_string = "UPDATE user_contact SET name = '$new_name', contact = '$new_number' WHERE ID = $ID AND contact = '$old_number'";
		mysql_query($query_string) or die($query_string);
	}else{ // if user wants to delete conatct
		$query_string = "DELETE FROM user_contact WHERE ID = $ID AND contact = '$new_number'";
		mysql_query($query_string) or die($query_string);
	}
}
?>