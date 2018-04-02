
<?php
    include('../connect.php');
    // $token=$_POST['token'];
    $deviceid=$_POST['deviceid'];
    $versions=$_POST['version'];
    if(isset($_POST['apk_id']))
    {
        $apk_id=$_POST['apk_id'];
    }
    else
    {
        $apk_id=1;
    }
    $status="active";
    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
   
    $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
    $update_query->execute();
    // $query=$db->prepare("select * from token_verify where token='$token' and deviceid='$deviceid'");
    // $query->execute();
    // $token_count=$query->rowCount();
    // if($token_count==1)//
    // {
        $type_query=$db->prepare("select d_id, device_type,image_allow from device where deviceid='$deviceid' and status='$status'");
        $type_query->execute();
        if($data_dev=$type_query->fetch())
        {
            do
            {
                $d_id=$data_dev['d_id'];
            }
            while($data_dev=$type_query->fetch());
        }

        $version_query=$db->prepare("select apk_version from device where deviceid='$deviceid'");
        $version_query->execute();
        while($apk_version_data=$version_query->fetch())
        {
            $apk_version=$apk_version_data['apk_version'];
        }
        if($apk_version<=$versions)
        {
            $version_update_query=$db->prepare("update device set apk_version='$versions' where deviceid='$deviceid'");
            $version_update_query->execute();
        }
        $sth = $db->prepare("select * from apk where apk_id='$apk_id'");
        $sth->execute();
        if ($data = $sth->fetch()) 
        {
            do 
            {
                $ver=$data['version'];
            } 
            while ($data = $sth->fetch());
            $responce = array('status' =>1,'message'=>$ver, "D_id"=>$d_id);
            echo json_encode($responce);
        
        }
        else
        {
          	$responce = array('status' =>0,'message'=>'Error Occured');
            echo json_encode($responce);
        }
    // }
    // else
    // {
    //     $responce = array('status' =>2,'message'=>"token mismatch");
    //     echo json_encode($responce);    
    // }

 ?>