$(document).ready(function() 
{
	var nm =  $('.name').text();
    var ret = nm.split(" "); 
    var k=(ret[0].charAt(0))+(ret[1].charAt(0));
   	$('#aviator').data("letters",k);
    $('#aviator').attr("data-letters",k);
});