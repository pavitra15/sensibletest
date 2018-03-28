$(document).ready(function() 
{
	$("input[type=number]").on("focus", function() 
	{
    	$(this).on("keydown", function(event) 
    	{
        	if (event.keyCode === 38 || event.keyCode === 40) 
        	{
            	event.preventDefault();
        	}
     	});
   	});
});