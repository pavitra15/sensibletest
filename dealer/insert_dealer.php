<?php
    session_start();
    include('../connect.php');
    $first_name     = $_POST['first_name'];
    $last_name      = $_POST['last_name'];
    $mobile         = $_POST['mobile'];
    $email          = $_POST['email'];
    $company_name   = $_POST['company_name'];
    $office_contact = $_POST['office_contact'];
    $website        = $_POST['website'];
    $address        = $_POST['address'];
    $city_id        = explode('_', ($_POST['city_id']), 2);
    $state_id       = $_POST['state_id'];
    $pincode        = $_POST['pincode'];
    $gst            = $_POST['gst'];
    $password       = $_POST['password'];

    $date           = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
    $created_by_date= $date->format('Y-m-d H:i:s');
    $access_control ='access';
    $status         ='inactive';
    $received_date=$date->format('l jS \of F Y');

    $info           = new SplFileInfo($_FILES['file_document']['name']);
    $extension      = pathinfo($info->getFilename(), PATHINFO_EXTENSION);

    $target_dir     = "C:/wamp64/www/my_node/";

    //For dealer code start
    $month_list = array(
        "A" => "01",
        "B" => "02",
        "C" => "03",
        "D" => "04",
        "E" => "05",
        "F" => "06",
        "G" => "07",
        "H" => "08",
        "I" => "09",
        "J" => "10",
        "K" => "11",
        "L" => "12"
    );
    
    $y = $date->format('y');
    $m = $date->format('m');
    foreach ($month_list as $mkey => $mvalue) {
        if ($m == $mvalue) {
            $month = $mkey;
        }
    }

    //For dealer code end
    try 
    {
        if ($_FILES["file_document"]["size"] > 14000) 
        {
            $response = array('info' => 'alert-info', 'message'=>"file size is greater than 14kb");
            echo json_encode($response);
        }
        else 
        {
            $query = $db->prepare("insert into dealer_mst(first_name,last_name,password,company_name,address,email,mobile,office_contact,city_id,state_id,gst,pincode,status,website,access_control,branding,password_updated_date,created_by_date)values('$first_name','$last_name',md5('$password'),'$company_name','$address','$email','$mobile','$office_contact','$city_id[1]','$state_id','$gst','$pincode','$status','$website','$access_control','0','$created_by_date','$created_by_date')");
            $query->execute();
            $count = $query->rowCount();
            if ($count > 0) 
            {
                $d_id = $db->lastInsertId();
                $document_name  = $d_id.".".$extension;
                $path           = $target_dir . $document_name;
                if(move_uploaded_file($_FILES['file_document']['tmp_name'], $path))
                {
                    $dealer_code = $city_id[0] . $month . $y . $d_id;
                    $query       = $db->prepare("update dealer_mst set dealer_code='$dealer_code', path='$path' where dealer_id='$d_id'");
                    $query->execute();
                    $response = array('info' => 'alert-info', 'message'=>"Registration done successfully");
                    echo json_encode($response);

                    include ('../mailin-smtp-api-master/Mailin.php');
                    $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                        <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <a href="https://www.sensibleconnect.com"><img src="https://glass-approach-179716.appspot.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle;">
                                        <span style="color: #a09bb9;">
                                            POSiBILL
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <div style="padding: 40px 40px 20px 40px; background: #fff;">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p>Dear Sir / Madam,</p>
                                                <p>We have received your dealer registration details on '.$received_date.'. These details will be subject to verification and the status of verification will be intimated to you by e-mail. </p>
                                                <p>Thank you,<br>The Sensible Connect Solutions Team</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align: center; font-size: 12px; color: #a09bb9; margin-top: 20px">
                                <p>
                                    Sensible Connect solutions Pvt Ltd, Pune, 411009
                                    <br />
                                    © 2017 Sensible Connect solutions pvt Ltd. All Rights Reserved.
                                </p>
                            </div>
                        </div>
                    </div>';
                    $mailin = new Mailin('info@sensibleconnect.com', 'QUT6g8qdZ7XmVn49');
                    $mailin->
                        addTo($email, 'Sensible Connect')->
                        setFrom('info@sensibleconnect.com', 'Sensible Connect')->
                        setReplyTo('info@sensibleconnect.com','Sensible Connect')->
                        setSubject('Dealer Registration')->
                        setText($message)->
                        setHtml($message);
                    $mailin->send();           
                } 
                else 
                {
                    $response = array('info' => 'alert-danger', 'message'=>"Some error occured");
                    echo json_encode($response);
                }    
            }
        }    
    }
    catch (Exception $e) {
        $response = array('info' => 'alert-danger', 'message'=>$e);
        echo json_encode($response);
    }
?>