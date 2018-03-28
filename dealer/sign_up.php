<style type="text/css">
    input[type=number]::-webkit-inner-spin-button 
    {
        -webkit-appearance: none;
    }
</style>
<?php
    include('../connect.php');         
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Registration | Sensible Connect - Dealer</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
</head>

<body class="signup-page">
    <div class="signup-box">
        <div class="logo">
            <a href="javascript:void(0);">SENSIBLE - <b>POS</b></a>
            <small>RELIABLE. STURDY. CONNECTED.</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_up" method="POST" onsubmit="return false" enctype="multipart/form-data">
                    <div class="msg">DELAER REGISTRATION </div>
                    <div id="phase1">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required autofocus>                               
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">call</i>
                            </span>
                            <div class="form-line">
                                <input type="number" class="form-control" name="mobile" id="mobile" required placeholder="Mobile" minlength="10" maxlength="10">                          
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">contact_mail</i>
                            </span>
                            <div class="form-line">
                                <input type="email" class="form-control" name="email" id="email" required placeholder="Email">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">work</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" id="company_name" name="company_name" required placeholder="Company Name">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">email</i>
                            </span>
                            <div class="form-line">
                                <input type="url" class="form-control" name="website" id="website" required placeholder="Website">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">contact_phone</i>
                            </span>
                            <div class="form-line">
                                <input type="number" class="form-control" name="office_contact" id="office_contact" placeholder="Office Contact" minlength="11" maxlength="11" title="Exceedes 11 digits">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">picture_in_picture</i>
                            </span>
                            
                            <div class="form-line">    
                                <label for="logo" style="font-weight: normal !important;color: #9e9e9e !important;">Choose Company Logo</label>                            
                                <input type="file" class="form-control" id="logo" name="logo" accept=".jpg, .jpeg, .png" required>                                   
                            </div>
                            <label id="size-error" style="font-size: 12px;display: block;margin-top: 5px;font-weight: normal;color: #F44336;"></label>
                        </div>
                        <button class="btn btn-block btn-lg bg-pink waves-effect" id="next">Next</button>
                    </div>
                </form>
                <form id="sign_up2" method="POST" onsubmit="return false" enctype="multipart/form-data">
                    <div id="phase2">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">business</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                               <i class="material-icons">location_city</i>
                            </span>
                            <div class="form-line">
                                <select class="form-control show-tick" id="state" name="state" data-live-search="true" required title="Please Choose State">
                                        <option value="0">Choose State</option>
                                        <?php
                                            $state_query=$db->prepare("select state_id, state_name, state_code from state_mst where status='active'");
                                            $state_query->execute();
                                            if ($dat=$state_query->fetch()) {
                                                do {
                                                    echo '<option value="'.$dat['state_id'].'">'.$dat['state_name'].' </option>';
                                                } while ($dat=$state_query->fetch());
                                            }
                                        ?>
                                </select>
                            </div>
                            <label id="state-error" style="font-size: 12px;display: block;margin-top: 5px;font-weight: normal;color: #F44336;"></label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">location_city</i>
                            </span>
                            <div class="form-line">
                                <select class="form-control show-tick" id="city" name="city" data-live-search="true">
                                    <option value="0">Choose City</option>
                                </select>
                            </div>
                            <label id="city-error" style="font-size: 12px;display: block;margin-top: 5px;font-weight: normal;color: #F44336;"></label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">place</i>
                            </span>
                            <div class="form-line">
                                <input type="number" class="form-control" id="pincode" name="pincode" placeholder="Pincode" required minlength="6" maxlength="6" >
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">payment</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" id="gst" name="gst" placeholder="GST No" required>
                            </div>
                        </div>                  
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input type="password" class="form-control" name="password" id="password" minlength="6" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input type="password" class="form-control" name="confirm" id="confirm" minlength="6" placeholder="Confirm Password" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="checkbox" name="terms" id="terms" id="terms" class="filled-in chk-col-pink">
                            <label for="terms">I read and agree to the <a href="terms/index">terms of usage</a>.</label>
                        </div>
                        <button class="btn btn-block btn-lg bg-pink waves-effect" id="back" style="width:40%;">BACK</button>
                        <button class="btn btn-block btn-lg bg-pink waves-effect" id="dealer_register" style="width:55%;margin-top:0px !important">SIGN UP</button>
                    </div>
                    <div class="m-t-25 m-b--5 align-center">
                        <a href="../login/signin">You already have a membership?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function() 
        {
            var isSelected = function(control)
            {
                var ControlName= document.getElementById(control);
                var error='#'+control+"-error";
                
                if(ControlName.options["selectedIndex"]!=-1)
                {
                    var value=ControlName.options[ControlName.selectedIndex].value;
                    if(value=="0")
                    {
                        $(error).text("Please Select this field");
                        return false;
                    }
                    else
                    {
                        $(error).text("");
                        return true;
                    }
                }
                else
                {
                    $(error).text("No fields are available");
                    return false;
                }
            }

            $('#logo').bind('change', function() {
                if((this.files[0].size)>14000)
                {
                    $('#size-error').text("file size is up to 14kb.");
                }
                else
                {
                    error=1;
                    $('#size-error').text("");
                }
            });

            function validate_size()
            {
                if(($('#logo')[0].files[0].size)>14000)
                {
                    $('#size-error').show();
                    $('#size-error').text("file size is up to 14kb.");
                    return false;
                }
                else
                {
                    $('#size-error').text("");
                    return true;
                }
            }

            $('#phase1').show();
            $('#phase2').hide();
            $('#next').click(function()
            {
                var phase1=$('#sign_up').validate(); 
                if(phase1.checkForm() &&  validate_size())
                {   
                    $('#phase1').hide();
                    $('#phase2').show();
                }
            })
            $('#back').click(function()
            {
                $('#phase1').show();
                $('#phase2').hide();
            })
            
            $('#dealer_register').click(function() 
            {
                var phase2=$('#sign_up2').validate();
                isSelected('city');
                if(isSelected('state') && isSelected('city') && phase2.checkForm() &&  validate_size())
                {
                    var data = new FormData();
                    data.append('first_name', $('#first_name').val());
                    data.append('last_name', $('#last_name').val());
                    data.append('mobile', $('#mobile').val());
                    data.append('website', $('#website').val());
                    data.append('office_contact', $('#office_contact').val());
                    data.append('company_name', $('#company_name').val());
                    data.append('email', $('#email').val());
                    
                    var file_document = $('#logo')[0].files[0];
                    data.append('file_document', file_document);
                    data.append('address', $('#address').val());
                    data.append('pincode', $('#pincode').val());               
                    data.append('password', $('#password').val());
                    data.append('gst', $('#gst').val());
                    data.append('terms', $('#terms').val());


                    var e = document.getElementById('state');
                    state_id = e.options[e.selectedIndex].value;
                    data.append('state_id', state_id);

                    var c = document.getElementById('city');
                    city_id = c.options[c.selectedIndex].value;
                    data.append('city_id', city_id);

                    $.ajax({
                        type: "POST",
                        url: "insert_dealer.php",
                        cache: false,
                            dataType: 'json',
                            processData: false,
                            contentType: false, 
                            data: data,
                        success: function(dat) {
                            console.log(dat);
                            showNotification(dat.info,dat.message, "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                         },
                         error: function(dat)
                         {
                            console.log(dat);
                         }
                    });

                }          
            });
            
            var getState = function() 
            {
                var e = document.getElementById('state');
                state_id = e.options[e.selectedIndex].value;

                $.ajax({
                    type: "POST",
                    url: "../admin/search_Cities.php",
                    data: {
                        state_id: state_id
                    },
                    success: function(dat) {
                        if (dat) {
                            $("#city").html(dat);
                        }
                    }
                });

            }    
            $('#state').change(function(){
                getState();
            }) 

        });

    </script>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            $("input[type=number]").on("focus", function() 
            {
                $(this).on("keydown", function(event) 
                {
                    if (event.keyCode === 38 || event.keyCode === 40 || event.keyCode === 69) 
                    {
                        event.preventDefault();
                    }
                });
            });
        });
        
    </script>    
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../plugins/jquery-validation/jquery.validate.js"></script>

    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/sign-up.js"></script>
</body>

</html>