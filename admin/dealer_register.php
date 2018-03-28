<?php
session_start();
include('admin_verify.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Welcome | Sensible Connect - Admin</title>
      <!-- Favicon-->
        <link rel="icon" href="../favicon.png" type="image/x-icon">
      <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
      <!-- Bootstrap Core Css -->
        <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
      <!-- Waves Effect Css -->
        <link href="../plugins/node-waves/waves.css" rel="stylesheet" />
      <!-- Animation Css -->
        <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
      <!-- Colorpicker Css -->
        <link href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />
      <!-- Dropzone Css -->
        <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">
      <!-- Multi Select Css -->
        <link href="../plugins/multi-select/css/multi-select.css" rel="stylesheet">
      <!-- Bootstrap Spinner Css -->
        <link href="../plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">
      <!-- Bootstrap Tagsinput Css -->
        <link href="../plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
      <!-- Bootstrap Select Css -->
        <link href="../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
      <!-- noUISlider Css -->
        <link href="../plugins/nouislider/nouislider.min.css" rel="stylesheet" />
      <!-- Custom Css -->
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/aviator.css" rel="stylesheet">
      <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
        <link href="../css/themes/all-themes.css" rel="stylesheet" />
    </head>
    <body class="theme-teal">
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-red">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
                <p>Please wait...</p>
            </div>
        </div>
      <div class="overlay"></div>
      <nav class="navbar">
         <div class="container-fluid">
            <div class="navbar-header">
               <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
               <a href="javascript:void(0);" class="bars"></a>
               <a class="navbar-brand" href="../cumulative/index">SENSIBLE CONNECT</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                <?php
                    // include('../notification/device_notification.php');
                ?>
                </ul>
            </div>
         </div>
        </nav>
      <!-- #Top Bar -->
        <section>
         <!-- Left Sidebar -->
            <aside id="leftsidebar" class="sidebar">
                <?php
                    include('../user_menu.php');    
                ?>
            <div class="menu">
               <ul class="list">
                  <li class="active">
                     <a href="../dashboard/index">
                     <i class="material-icons">home</i>
                     <span>Home</span>
                     </a>
                  </li>
               </ul>
            </div>
           
            <?php
                include('../footer.html');
            ?>
            <!-- #Footer -->
         </aside>
         <!-- #END# Left Sidebar -->
         <!-- Right Sidebar -->
         <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
               <li role="presentation" class="active"><a href="#skins" data-toggle="tab">DEVICE</a></li>
               <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <?php
// if(isset($_SESSION['d_id']))
// {   
//     include('../right_menu.html');
// } 
?>
                  
               <div role="tabpanel" class="tab-pane fade" id="settings">
                 <?php
// if(isset($_SESSION['d_id']))
// {   
//     include('../setting.php');
// } 
?> 
               </div>
            </div>
         </aside>
         <!-- #END# Right Sidebar -->
      </section>
      <section class="content">
         <div class="container-fluid">
            <div class="block-header">
               <h2>DEALER REGISTRATION</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
               <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                  <div class="card">
                     <div class="body">
                         <?php
// switch($flag)
// {
//     case 6:
//         echo'<div class="alert bg-red alert-dismissible" role="alert">
//             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//                 Invalid Serial number!
//         </div>';
//     break;
//     case 2:
//         echo'<div class="alert bg-red alert-dismissible" role="alert">
//             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//                 Please contact to customer support
//         </div>';
//     break;
//     case 11:
//         echo'<div class="alert bg-green alert-dismissible" role="alert">
//             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//                 Login Success, please register device here
//         </div>';
//     break;
//     default:
//     break;
// }

?>  
                        <form id="form_validation" method="POST" enctype="multipart/form-data">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="first_name" id="first_name" onkeyup="validate(this,'errorFN')">
                                        <label id="errorFN" class="form-label">First Name*</label>  
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="mobile" id="mobile" required onkeyup="validate(this,'error_mobile')" maxlength="10">
                                        <label id="error_mobile" class="form-label">Mobile*</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="company_name" name="company_name" required onkeyup="validate(this,'errorCN')">
                                        <label id="errorCN" class="form-label">Company Name*</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="website" id="website" required>
                                        <label class="form-label">Website</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="form-label" id="error_state" style="font-size: 14px">State*</label>
                                        <select class="form-control show-tick" id="state" name="state" onchange="getState()" data-live-search="true" >
                                        <option value="0">Choose</option>
                                            <?php
                                                $state_query = $db->prepare("select state_id, state_name, state_code from state_mst where status='active'");
                                                $state_query->execute();
                                                if ($dat = $state_query->fetch()) {
                                                    do {
                                                        echo '<option value="' . $dat['state_id'] . '">' . $dat['state_name'] . ' </option>';
                                                    
                                                    } while ($dat = $state_query->fetch());
                                                }
                                            ?>         
                                        </select>
                                    </div>
                                </div>
                              
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="pincode" name="pincode" maxlength="6" onkeyup="validate(this,'error_pincode')">
                                        <label id="error_pincode" class="form-label">PinCode*</label>
                                    </div>
                              </div>
                           </div>

                           <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              <div class="form-group form-float">
                                 <div class="form-line">
                                    <input type="text" class="form-control" id="last_name" name="last_name" required onkeyup="validate(this,'errorLN')">
                                    <label id="errorLN" class="form-label">Last Name*</label>
                                 </div>
                              </div>
                              <div class="form-group form-float">
                                 <div class="form-line">
                                    <input type="Email" class="form-control" name="email"  id="email">
                                    <label class="form-label" id="errorEmail">Email*</label>
                                 </div>
                              </div>
                              <div class="form-group form-float">
                                 <div class="form-line">
                                    <input type="text" class="form-control" name="office_contact" id="office_contact" maxlength="11">
                                    <label id="error_office_contact" class="form-label">Office Contact</label>
                                 </div>
                              </div>
                              <div class="form-group form-float">
                                 <div class="form-line">
                                    <input type="text" class="form-control" id="address" name="address" required onkeyup="validate(this,'errorAdr')">
                                    <label id="errorAdr" class="form-label">Address*</label>
                                 </div>
                              </div>
                              <div class="form-group form-float">
                                 <div class="form-line">
                                    <label class="form-label" id="error_city" style="font-size: 14px">City*</label>
                                    <select class="form-control show-tick" id="city" name="city" data-live-search="true">
                                        <option value="0">Choose</option>
                                    </select>
                                 </div>
                              </div>
                              
                              <div class="form-group form-float">
                                 <div class="form-line">
                                   
                                    <input type="file" class="form-control" id="file_document" name="file_document">
                                   
                                 </div>
                              </div>
                           </div>
                           <button class="btn btn-primary waves-effect" id="dealer_register" name="dealer_register" type="button">REGISTER</button>
                          <button class="btn btn-primary waves-effect" id="cancel" name="cancel" type="button">Cancel</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <!--#END# DateTime Picker -->
         </div>
      </section>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
            var state_id;
            var city_id;
            var IsNumber=function(e){
                if(e.charCode<48 || e.charCode>57){
                    e.preventDefault();
                    // showNotification("alert-danger", "Only numbers are alowed!!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                }
            }
    
            var IdValidLength=function(control,maxLen){
                var controlName ='#'+control;
                if ($(controlName).val()!='') {
                    if($(controlName).val().length<maxLen){
                        showNotification("alert-danger",control + " number should contain "+ maxLen +" digits!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                        document.getElementById('error_'+control).style.color="red";
                        return false;
                    }else{
                        document.getElementById('error_'+control).style.color="#aaa";
                        return true;
                    }
                } 
            }

        var IsStateCitySelected =function(control){
            var controlName = document.getElementById(control);
            if (controlName.options["selectedIndex"]!=-1) {
                var value= controlName.options[controlName.selectedIndex].value;  
                if(value=="0"){
          // showNotification("alert-danger",control + " number should contain "+ maxLen +" digits!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    document.getElementById('error_'+control).style.color="red";
                    return false;
                }else{
                    document.getElementById('error_'+control).style.color="#aaa";
                     return true;
                }  
            }else{
                showNotification("alert-danger", "Cities are not lisdted for selected state!!!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
            }
        }
    
        var IsEmail=function(){
            var email=document.getElementById('email').value;
            if(email!=''){
                if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))){
                    showNotification("alert-danger", "Please Enter Valid Email Id!!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    document.getElementById('errorEmail').style.color="red";
                    return false;
                }else{
                    document.getElementById('errorEmail').style.color="#aaa";
                    return true;
                } 
            }
        }
    
    $(document).ready(function() {
      // $('#error_city').hide();
      // $('#error_state').hide();
                $('#dealer_register').click(function() {                    
                  // if(form_validate()){
                    if(true){
                    var data = new FormData();

                    data.append('first_name',$('#first_name').val());
                    data.append('last_name',$('#last_name').val());
                    data.append('mobile',$('#mobile').val());
                    data.append('website',$('#website').val());
                    data.append('address',$('#address').val());
                    data.append('pincode',$('#pincode').val());
                    data.append('office_contact',$('#office_contact').val());
                    data.append('company_name',$('#company_name').val());
                    data.append('email',$('#email').val());

                    var e = document.getElementById('state');
                    state_id = e.options[e.selectedIndex].value; 
                    data.append('state_id',state_id);
                    
                    var c = document.getElementById('city');
                    city_id = c.options[c.selectedIndex].value;
                    data.append('city_id',city_id);
                    
                    var file_document = $('#file_document')[0].files[0];
                    data.append('file_document',file_document);
                    console.log(JSON.stringify(data));
                    $.ajax({
                        type: "POST",
                        url: "insert.php",
                        dataType: 'json', // what to expect back from the PHP script
                        cache: false,
                        contentType: "applcation/json",
                        processData: false,
                        data: JSON.stringify(data),
                        //enctype="multipart/form-data",
                        success:function(res){
                          console.log(res);
                        },
                         error:function(res){
                          console.log(res);
                        }
                    });
                    }else{
                         showNotification("alert-danger", "Please Fill Mandatory Information", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                    }
                    
                });
                $('#cancel').click(function(){
                   window.location="dealer_register.php";
                });                
                $('#mobile').keypress(function(e) {
                  IsNumber(e);
                });
                 $('#mobile').focusout(function(e){
                  // if($('#mobile').val().length<10){
                  //     showNotification("alert-danger", "Mobile number should contain 10 digits!", "top", "center",'animated fadeInDown', 'animated fadeOutUp');
                  // }
                  IdValidLength('mobile',10);
                })
                $('#pincode').keypress(function(e) {
                  IsNumber(e);
                  });
                 $('#pincode').focusout(function(e) {
                  IdValidLength('pincode',6);
                  });
                $('#office_contact').keypress(function(e) {
                  IsNumber(e);
                });
                 // $('#office_contact').focusout(function(e) {
                 //  IdValidLength('office_contact',11);
                 //  });
                $('#email').focusout(function(e){
                  IsEmail();
                })

            });
    var validate=function(control,errorLable){
          
          if(control.value==""){
             document.getElementById(errorLable).style.color="red";
          }else{
            document.getElementById(errorLable).style.color="#aaa";
          }
      
    }  
    var form_validate=function(){
      // debugger;
      var flag=true;
      if(document.getElementById('first_name').value==''){

        document.getElementById('errorFN').style.color = "red";
        flag=false;
      }
      if(document.getElementById('last_name').value==''){
        document.getElementById('errorLN').style.color = "red";
        flag=false;
      }
      if(document.getElementById('mobile').value==''){
        document.getElementById('error_mobile').style.color = "red";
         flag=false;
      }
      if(document.getElementById('company_name').value==''){
        document.getElementById('errorCN').style.color = "red";
        flag=false;
      }
      if(document.getElementById('address').value==''){
        document.getElementById('errorAdr').style.color = "red";
        flag=false;
      }  
      if(document.getElementById('pincode').value==''){
        document.getElementById('error_pincode').style.color = "red";
        flag=false;
      }
      if(document.getElementById('email').value==''){
        document.getElementById('errorEmail').style.color = "red";
        flag=false;
      }
      if(!IsStateCitySelected('state')){
        flag=false;
      }
      if(!IsStateCitySelected('city')){
        flag=false;
      }
      if(!IsEmail()){
        flag=false;
      }

      if(!IdValidLength('mobile',10)){
        flag=false;
      };

      if(!IdValidLength('pincode',6)){
        flag=false;
      };

      if(!IdValidLength('office_contact',11)){
        flag=false;
      };
      
      return flag;                   
                    
    }
    var getState = function() {
        var e = document.getElementById('state');
        state_id = e.options[e.selectedIndex].value;

        $.ajax({
            type: "POST",
            url: "search_cities.php",
            data: {
                state_id: state_id
            },
            success: function(dat) {
              if(dat){
                  $("#city").html(dat);
              }
            }
        });
    }
</script>      
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="../js/change_device.js"></script>

    <script src="../js/avatar.js"></script>

    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <script src="../plugins/node-waves/waves.js"></script>

    <script src="../plugins/jquery-datatable/jquery.dataTables.js"></script>

    <script src="../plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>


    <script src="../plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>

    <script src="../plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>

    <script src="../plugins/jquery-datatable/extensions/export/jszip.min.js"></script>

    <script src="../plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>

    <script src="../plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>

    <script src="../plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>

    <script src="../plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <script src="../js/admin.js"></script>

    <script src="../js/pages/tables/jquery-datatable.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/demo.js"></script>
   </body>
</html>