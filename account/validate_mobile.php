<?php
	include('../connect.php');
	$mobile=$_POST['mobile'];
	$sth=$db->prepare("select * from login_mst where username='$mobile'");
    $sth->execute();
    $cnt=$sth->rowCount();
    if($cnt==0)
    {
        echo "2";
    }
    else
    {
    	echo "1";
	}
?>