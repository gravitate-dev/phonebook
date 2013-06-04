<?php
include_once("phonebook.inc.php");
include_once("../kint/Kint.class.php");

if (isset($_SESSION['number']))
	setcookie('number', $_SESSION['number']);
if (!isset($_SESSION['number']) && !isset($_COOKIE['number']))
	echo<<<heredoc
	<script>
	window.location = "index.php";
	</script>
heredoc;
?>
<html>
<head>
<title>Phone Book</title>
<script src="js/jquery.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link href="css/styles.css" rel="stylesheet"/>
<link href="css/stylescontact.css" rel="stylesheet"/>
<script>

$(function(){
function lol(ele)
{
	ele.removeClass("firststyle").addClass("secondstyle");
}

function selectedBox(that)
{
	$('.selected-contact').removeClass('selected-contact');
	that.addClass('selected-contact');
	that.addClass('firststyle');
	setTimeout(function(){ lol(that);},1000);
	$('textarea').html('').val('');
}

//makes each one flip
$('.firststyle').each(function(index){
	setTimeout(function(){ lol($(".firststyle").last());},200*index);
});

//register the click
$('.box-content').click(function(){
selectedBox($(this))
});

$("button").click(function(){
return false;
});


$("div.box-content").click( function() {
//TODO: send the background image to the contact
$.get("model/sendtext.php", { number:$('.selected-contact').data("number"), txt:$('.textarea').html()}, function done(data){
});
$('.textarea').html('').val('');
});

var onclickoptions =  function() {
var opt = $(this).data("option");
var oldn = $(this).data("number");
switch (opt)
{
	case "add":
	$('#option').val('add');
	break;
	
	case "delete":
	$('#option').val('delete');
	$(this).closest('.box-content').hide();
	$.get("model/editcontact.php", { old_number: oldn, new_number: $(this).data("number"), option:opt}, function done(data){
});
	break;
	
	case "edit":
	$('#option').val('edit');
	break;
}
$('#old_number').val(oldn);
//TODO: send the background image to the contact

};
$("button[data-option]").click(onclickoptions);

var onclickedit = function() {
$('#addcontact_dialog').show();
$('#contactWrapper').css('opacity',0.2);
};

$("button[data-option=edit]").click(onclickedit);


$(' #contactWrapper').click(function() {
$("#addcontact_dialog").fadeOut();
	$('#contactWrapper').css('opacity',1);
});
$('#addcontact_dialog form').submit(function() {
    var values = $(this).serialize();
	$.get("model/editcontact.php?"+values,function done(data){
	
	if ($('#option').val()=="add")
	{
	var new_html = '<div class="secondstyle box-content box-content-wider" data-number="'+$('#new_number').val()+'"><strong class="big-text">'+$('#new_name').val()+'</strong><br><strong class="big-text">'+$('#new_number').val()+'</strong><br><span class="contact-button-container"><button style="left:50px; float:right;margin-left:50px;" data-option="edit" data-number="'+$('#new_number').val()+'">Edit Contact</button><button style="float:right;margin-left:50px;" data-option="delete" data-number="'+$('#new_number').val()+'">Delete Contact</button></span><div class="spacer-contact">&nbsp </div></div>';
		
	$(new_html).insertAfter('.box-content:last');
	$("button[data-option]").click(onclickoptions);

$("div.box-content").click( function() {
//TODO: send the background image to the contact
$.get("model/sendtext.php", { number:$('.selected-contact').data("number"), txt:$('.textarea').html()}, function done(data){
});
$('.textarea').html('').val('');
});
	//rebind dom
	
	} else if ($('#option').val()=="edit")
	{
		$('.box-content[data-number='+$('#old_number').val()+'] strong:eq(0)')
		.html($('#new_name').val());
		
		$('.box-content[data-number='+$('#old_number').val()+']').children('strong:eq(1)')
		.html($('#new_number').val());
		
	}
	
	$("#addcontact_dialog").fadeOut();
	$('#contactWrapper').css('opacity',1);
	});
});
$("#contact_add").click( function() {
//TODO: send the background image to the contact
$('#addcontact_dialog').show();
$('#contactWrapper').css('opacity',0.2);
$('#phoneOptionForm').val('edit');
});

});
</script>
</head>
<body>
<div id="contactWrapper">
<nav>
    <ul>
      <li><a>Phonebook</a></li>
    </ul>
</nav>
<div class="containerContacts">
	<div class="leftSide">
		<button data-option='add' id="contact_add">Click to add new contact</button>
		<?php 
		$number = $_COOKIE['number'];
	$contact = getContacts($number);
	$numbers = array();
	foreach ($contact as $key) {
	extract($key);
echo<<<contactBox
	<div class="firststyle box-content box-content-wider" data-number="$contact">
		<strong class="big-text">$name</strong><br>
		<strong class="big-text">$contact</strong><br>
		<span class="contact-button-container">
		<button style="left:50px; float:right;margin-left:50px;" data-option='edit' data-number=$contact>Edit Contact</button>
		<button style="float:right;margin-left:50px;" data-option='delete' data-number=$contact>Delete Contact</button>
		</span>
		<div class="spacer-contact">&nbsp </div>
		</div>
contactBox;
	}
?>
	</div>
	<div class="rightSide">
	<div class="solid" style="position:fixed; bottom:100px;">
		<div class="speechbubble">
		<div class="textarea" contentEditable="true" placeholder="Type message then click on a contact to text them">Type message then click on a contact to text them</div>
		</div>
	</div> 	
	</div>
</div>
</div>
	<div class="dialog" id="addcontact_dialog" style="display:none;">
	<form onsubmit="return false;">
		<label for="new_name">Name:</label><input id="new_name" name="new_name" type="text"/><span class="error" style="display:none;">Name too long</span><br>
		<label for="new_number">Number:</label><input id="new_number" name="new_number" type="number" pattern="\d*" placeholder="Phone Number"/>
		<input id="option" type="hidden" name="option" value="add"/>
		<input id="old_number" name="old_number" type="hidden"/><br>
		<input id="editContactInvoke" type="submit" value="add/update"/>
	</form>
	</div>
</body>
</html>