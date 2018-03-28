<?php
    include('../connect.php');
    // $token=$_POST['token'];
    $deviceid=$_POST['deviceid'];
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
    // if(1)//$token_count==1
    // {
        $sth = $db->prepare("select * from apk where apk_id=1");
        $rs= $sth->execute();
        if ($data = $sth->fetch()) 
        {
          	do 
          	{
            	$path=$data['paths'];
            	$name=$data['name'];
          	} 
          	while ($data = $sth->fetch());
        }       
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($name));
        ob_clean();
        flush();
        readfile($path); //showing the path to the server where the file is to be download
        exit;
    // }
    // else
    // {
    //     $responce = array('status' =>2,'message'=>"token mismatch");
    //     echo json_encode($responce);
    // }
?>