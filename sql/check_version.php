<?php
	include('../connect.php');
	if(is_ajax())
	{
		$apk_version=0;
		$d_id=$_POST['d_id'];
		$count_query=$db->prepare("select apk_version from device, apk where d_id='$d_id' and apk_version<version");
		$count_query->execute();
		if($data=$count_query->fetch())
		{
			do
			{
				$apk_version=$data['apk_version'];
			}
			while ($data=$count_query->fetch());
		}

		if($apk_version)
			echo $apk_version;
		else
			echo $apk_version;
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>