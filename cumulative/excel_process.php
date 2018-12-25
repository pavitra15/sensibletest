<?php
    session_start();
    include('../validate.php');     
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome | Sensible Connect - Admin</title>
    <link rel="icon" href="../favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <link href="../css/style.css" rel="stylesheet">
    
    <link href="../css/aviator.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
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
                        include('../notification/notification.php');
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <section>
        <aside id="leftsidebar" class="sidebar">
            <?php 
                include('../user_menu.php');
            ?>
             <div class="menu">
                <ul class="list">
                    <li class="active">
                        
                    </li>
                </ul>
            </div>
            <?php include('../footer.html'); ?>
        </aside>
    </section>
    <?php
        $id=$_SESSION['login_id'];
        $d_id=$_SESSION['d_id'];
        $status="active";
        $name_query=$db->prepare("select device_name,tax_type,prnt_billno, prnt_billtime, model from device where d_id='$d_id'");
        $name_query->execute();
        while ($name_data=$name_query->fetch()) 
        {
            $device_name=$name_data['device_name'];
            $device_model=$name_data['model'];
            $tax_type=$name_data['tax_type'];
            $prnt_billno=$name_data['prnt_billno'];
            $prnt_billtime=$name_data['prnt_billtime'];
        }
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    PRODUCT SETTING
                </h2>
            </div>
            <div class="row card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="body">
                        <input type="file" id="files" name="files"/>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data-display">
                        
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " style="margin-bottom: 15px">
                        <button class="btn waves-effect" id="skip">SKIP</button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 15px; text-align: right;">
                        <button class="btn btn-primary waves-effect" id="save">CONTINUE</button>
                    </div>
                </div>
                <div class="frmSearch">
                    <div id="suggesstion-box"></div>
                </div>
            </div>
        </section>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script>

        $(document).ready(function() 
        {  
            var page="1";
            var device_model= <?php echo $device_model; ?>;
            $('#data-display').show();
            $('.page-loader-wrapper').show();
            $.ajax({
                type: 'POST',
                url: 'data.php',
                data: { "page":page ,"device_model": device_model},
                cache: false,
                success: function(data)
                {
                    $('.page-loader-wrapper').hide();
                    $('#data-display').html(data);
                }
            });
        });

       function handleFile(e) {
         //Get the files from Upload control
            var files = e.target.files;
            var json_object;
            var i, f;
         //Loop through files
            for (i = 0, f = files[i]; i != files.length; ++i) {
                var reader = new FileReader();
                var name = f.name;
                reader.onload = function (e) {
                    var data = e.target.result;
                    var result;
                    var workbook = XLSX.read(data, { type: 'binary' });
                    var sheet_name_list = workbook.SheetNames;

                    var sheetName='Product';
                    var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                    json_object = JSON.stringify(XL_row_object);
                    console.log(json_object);
                    CreateTableFromJSON(); 
                    function CreateTableFromJSON() {
                        json_object=JSON.parse(json_object);
                        var col = [];
                    
                        for (var i = 0; i < json_object.length; i++) {
                            for (var key in json_object[i]) {
                                if (col.indexOf(key) === -1) {
                                    col.push(key);
                                }
                            }
                        }

                        var table = document.createElement("table");

                        var tr = table.insertRow(-1);                   // TABLE ROW.

                        for (var i = 0; i < col.length; i++) {
                            var th = document.createElement("th");      // TABLE HEADER.
                            th.innerHTML = col[i];
                            tr.appendChild(th);
                        }

                        for (var i = 0; i < json_object.length; i++) {

                            tr = table.insertRow(-1);

                            for (var j = 0; j < col.length; j++) {
                                var tabCell = tr.insertCell(-1);
                                tabCell.innerHTML = json_object[i][col[j]];
                            }
                        }

                        var divContainer = document.getElementById("data-display");
                        divContainer.innerHTML = "";
                        divContainer.appendChild(table);
                    }
                };
                reader.readAsArrayBuffer(f);
            }
        }

      //Change event to dropdownlist
      $(document).ready(function(){
        $('#files').change(handleFile);
      });
    </script>
    <script src="../js/change_device.js"></script>
    <script src="../js/avatar.js"></script>

    
    <script src="../js/pages/ui/dialogs.js"></script>
    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="../plugins/raphael/raphael.min.js"></script>
    <script src="../plugins/morrisjs/morris.js"></script>

    <script src="../plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="../js/pages/ui/notifications.js"></script>

    <script src="../js/admin.js"></script>

    <script src="../js/demo.js"></script>

    <script src="../js/avatar.js"></script>
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    
    <script src="../plugins/node-waves/waves.js"></script>
    <script src="../js/admin.js"></script>
    
</body>

</html>