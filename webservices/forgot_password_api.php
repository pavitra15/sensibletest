<?php
	include('../connect.php');
	$username=$_POST['username'];
    $query=$db->prepare("select id from login_mst where username='$username'");
    $query->execute();
    $count=$query->rowCount();
    $status='active';
	if($count==1)
    {
        $select_query=$db->prepare("select * from login_mst where username='$username' and status='$status' and access_control='$status'");
        $select_query->execute();
        $count=$select_query->rowCount();
        if ($count==1)
        {
            if($data=$select_query->fetch())
            {
                do
                {
                    $password=$data['password'];
                }
                while ($data=$select_query->fetch());
            }
            if($password!="")
            {
                $pass=base64_decode($password);
                $key='123acd1245120954';
                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                $decode_password = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $pass, MCRYPT_MODE_ECB, $iv));
                $curl = curl_init();

                $message   =  $decode_password." is your POSiBILL account password";

                curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.msg91.com/api/v2/sendsms",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '{"sender": "SOCKET", "route": 4, "country": 91, "sms": [ { "message": "'.$message.'", "to": [ "'.$username.'"] } ] }',
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                "authkey:153437AHNd3Hcat5923dae5",
                "content-type: application/json"
                ),
                ));
               if(curl_exec($curl))
                {
                    $responce = array('status' =>3,'message'=>"Password send to your mobile");
                    echo json_encode($responce);
                }
                else
                {
                	$responce = array('status' =>2,'message'=>"Some technical error occured");
                    echo json_encode($responce);
                }
        	}
        }
        else
        {
            $responce = array('status' =>1,'message'=>"Contact to customer support");
            echo json_encode($responce);
        }
    }
    else
    {
    	$responce = array('status' =>0,'message'=>"Invalid user name");
        echo json_encode($responce);
    }
?>