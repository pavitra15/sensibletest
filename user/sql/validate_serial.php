<?php
	include('../connect.php');
	if(is_ajax())
    {
		$serial_no=$_POST['serial_no'];
		$sth=$db->prepare("select * from admin_device where serial_no='$serial_no'");
	    $sth->execute();
	    $cnt=$sth->rowCount();
	    if($cnt==0)
	    {
	        echo "1";
	    }
	    else
	    {
	    	$inact_sth=$db->prepare("select * from admin_device where serial_no='$serial_no' and status='inactive'");
		    $inact_sth->execute();
		    $cont=$inact_sth->rowCount();
		    if($cont==1)
		    {
		        echo "4";
		    }
		    else
		    {
				$use_sth=$db->prepare("select * from admin_device where serial_no='$serial_no' and used='use'");
		    	$use_sth->execute();
		    	$count=$use_sth->rowCount();
			    if($count==0)
			    {
			        echo "3";
			    }
			    else
			    {
		    		echo "2";
		    	}
		    }
	    }
	}
	else 
    {
        echo "something wrong";
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>