 function change_device(sk) 
{
    $.ajax({
        type: 'POST',
        url: '../change/change_device.php',
        data: { "q":sk},
        cache: false,
        success: function(data)
        {
            location.reload(true);
        }
    });
}