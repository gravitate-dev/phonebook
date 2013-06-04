<?php
session_start();
?>
<html>
<head>
<title>Phone Book</title>
<link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/>
<link href="css/styles.css" rel="stylesheet"/>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script>

function nextContent()
{
	$("#mainContainer").show('slide',{ direction: 'left' }, 500 );
}

function hideContent()
{
	$("#mainContainer").hide('slide',{ direction: 'right' },1000 );
}

	
function swapContent( content )
{
	hideContent();
	setTimeout(function(){ $('#mainContent form').hide(); content.show(); nextContent(); },1000);
}

//uses both number and id string to get the data
function getContentBox( index )
{
	var selectorString;
	var numberRegex = /^\d+$/;
	if (numberRegex.test(index)) { //its a number then get it by number
		if (index > $("#extraContent li").size())
			return "error";
		selectorString = '#extraContent li:nth-child('+index+')';
	} else {
		selectorString = '#extraContent li:nth-child('+index+')';
	}
	
	return $(selectorString).html();
}
var userPhone = new Object();
$(function() {
	
	$('form').submit(function() {
    var values = $(this).serialize();
	var id = $(this).children('input[type!=submit]').each(function(){
	//check the validity of the data here
	v = $(this).val();
	if ($(this).data("tag")=="phone_number" || $(this).data("tag")=="confirm_code")
		if((v.match(/^\d+$/) && v.length != 0 && v.length < 12) ) {
			if ($(this).attr("type")=="number") {
				//set all phone number values to this
				$('input[data-tag=phone_number]').val(v);
			}
		} else return false;
		
	if ($(this).attr("name")=="real_name") {
			//set all real names to this
			$('input[name=real_name]').val(v);
			}
	});
	
	$.get("model/checknumber.php?"+values, 
			function(data){
				if 		(data==0)swapContent($('#phoneNumberLI'));
				else if (data==1)swapContent($('#passwordLI')); //enter password
				else if (data==2)swapContent($('#confirmCodeLI')); 
				else if (data==3)window.location = "contacts.php";
				else if (data==4)swapContent($('#realnameLI'));
			});
	return false;
	});
});
</script>
</head>
<body>
<nav>
    <ul>
      <li><a>Home</a></li>
    </ul>
</nav>
<div id="mainContainer">
	<div id="mainContent" class="box-content" >
		<form id="phoneNumberLI">
			Enter your phone number to begin
			<input name="number" data-tag="phone_number" type="number" pattern= "\d*" placeholder="Phone Number"/>
			<input type="submit" value="enter"/>
		</form>
		
		<form id="confirmCodeLI" style="display:none;">
			Please enter your confirmation code
			<input name="code" data-tag="confirm_code" type="text" pattern= "\d*" placeholder="Confirmation Code"/>
			<input name="number" data-tag="phone_number" type="hidden"/>
			<input type="submit" value="confirm"/>
		</form>
		
		<form id="realnameLI" style="display:none;">
			Enter your first name
			<input name="real_name" data-tag="real_name" type="text" placeholder="Real Name"/>
			<input name="number" data-tag="phone_number" type="hidden"/>
			<input type="submit" value="enter"/>
		</form>
		
		<form id="passwordLI" style="display:none;" onsubmit="return false;">
			Please enter your password
			<input name="password" data-tag="register_password" type="password" placeholder="Password"/>
			<input name="number" data-tag="phone_number" type="hidden"/>
			<input type="submit" value="finish"/>
		</form>
		
		<form id="redirectLI" style="display:none;" >
			Taking you to the main page <a href="contacts.php">click here if it doesn't automatically redirect you</a>
		</form>
	</div>
</div>
<div id="number"></div>
<div id="fbdata"></div>
</body>
</html>