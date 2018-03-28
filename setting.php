
<div class="demo-settings">
    <p>SYSTEM SETTINGS</p>
    <ul class="setting-list">
        <li>
            <span>Print Bill No</span>
            <div class="switch">
                <div id="prnt_billno" style="display: none"><?php echo $prnt_billno; ?></div>
                <label>OFF<input type="checkbox" id="billno" checked><span class="lever switch-col-cyan"></span>ON</label>
            </div>
        </li>
        <li>
            <span>Print Time</span>
            <div class="switch">
                <div id="prnt_billtime" style="display: none"><?php echo $prnt_billtime; ?></div>
                <label>OFF<input type="checkbox" id="billtime" checked><span class="lever switch-col-cyan"></span>ON</label>
            </div>
        </li>
    </ul>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">

    $('#billno').on('change', function () {
        var billno = this.checked ? 'on' : 'off';
        var dataString = 'billno='+ billno+'&id='+<?php echo $_SESSION['login_id']; ?> +'&d_id='+<?php echo $_SESSION['d_id']; ?>;  
        $.ajax({
    type: "POST",
    url: "../update/update_prnt_bill_no.php",
    data: dataString,
    cache: false,
    success: function(data)
    {
        values=data.split('_');
    ch=values[0];
    name=values[1];
    mobile=values[2];
    switch(ch)
    {
        case "1":
            showNotification("alert-info", "Setting updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
        break;
    }
    }
    });
        
});

     $('#billtime').on('change', function () {
        var billtime = this.checked ? 'on' : 'off';
        var dataString = 'billtime='+ billtime+'&id='+<?php echo $_SESSION['login_id']; ?> +'&d_id='+<?php echo $_SESSION['d_id']; ?>;  
        $.ajax({
    type: "POST",
    url: "../update/update_prnt_bill_time.php",
    data: dataString,
    cache: false,
    success: function(data)
    {
         values=data.split('_');
    ch=values[0];
    name=values[1];
    mobile=values[2];
    switch(ch)
    {
        case "1":
            showNotification("alert-info", "Setting updated successfully", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
        break;
    }
    }
    });
        
});

    $(document).ready(function() {
        var prnt_billno;
        $('#prnt_billno').each(function(){
            prnt_billno=$(this).text();
        });
        if(prnt_billno==="on")
        {
            $('#billno').attr('checked', true);
        }
        else
        {
            $('#billno').attr('checked', false);
        }
    });

    $(document).ready(function() {
        var prnt_billtime;
        $('#prnt_billtime').each(function(){
            prnt_billtime=$(this).text();
        });
        if(prnt_billtime==="on")
        {
            $('#billtime').attr('checked', true);
        }
        else
        {
            $('#billtime').attr('checked', false);
        }
    });
</script>