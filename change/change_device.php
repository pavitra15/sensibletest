<?php
	session_start();
	include('../connect.php');
	$d_id=$_POST['q'];
    $_SESSION['person_config']="";

    $query=$db->prepare("select device.d_id, device_type, language_name, person_config from device, configuration, language_mst where device.d_id='$d_id' and language_mst.language_id=device.language_id and configuration.d_id=device.d_id");
    $query->execute();
    if($data=$query->fetch())
    {
    	do
        {
            $d_id=$data['d_id'];
        	$device_type=$data['device_type'];
            $device_language=$data['language_name'];
            $person_config=$data['person_config'];
        }
        while($data=$query->fetch());
        $_SESSION['d_id']=$d_id;
        $_SESSION['device_type']=$device_type;
        $_SESSION['device_language']=$device_language;
        $_SESSION['person_config']=$person_config;
    }
    $_SESSION['reset']=0;
    $_SESSION['configure']=0;
    echo $_SESSION['d_id'];
?>