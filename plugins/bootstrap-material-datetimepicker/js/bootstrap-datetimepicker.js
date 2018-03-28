
$(document).ready(function(){
	$.ajax({
        type: "POST",
        url: "../plugins/bootstrap-material-datetimepicker/js/date-time/bootstrap-datetimepicker.php",
        cache: false,
        success: function(data)
        {
        }
    });
});