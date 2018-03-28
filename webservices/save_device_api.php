<?php
    include('../connect.php');
    $date=date('Y-m-d');
    $deviceid=$_POST['deviceid'];

    $check_query=$db->prepare("select * from admin_device where deviceid='$deviceid'");
    $check_query->execute();
    $device_count = $check_query->rowCount();
    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    if ($device_count>0) 
    {
        $update_query=$db->prepare("update last_connect set city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login' where deviceid='$deviceid'");
        $update_query->execute();
    }
    else
    {
        $update_query=$db->prepare("insert into last_connect (deviceid, city, state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login')");
        $update_query->execute();
    }

    if(empty($_POST['deviceid']))
    {
        $response = array('status' =>0,'message'=>"empty value" );
        echo json_encode($response);
    }
    else
    {
        $sth = $db->prepare("select * from admin_device where deviceid='$deviceid'");     
        $sth->execute();
        $count = $sth->rowCount();
        if ($count>0) 
        {
            if($data = $sth->fetch())
            {
                do 
                    {
                        $serial=$data['serial_no'];

                    } 
                    while ($data = $sth->fetch());
            }
            $response = array('status' =>3,'serial'=>$serial,'message'=>"Device already register" );
            echo json_encode($response);
        }
        else
        {
            $prev_serial="";
            date_default_timezone_set('Asia/Kolkata');
            $year=date('y');
            $month=date('m');
            $sth = $db->prepare("select * from admin_device order by id desc limit 1");
            $sth->execute();
           if ($data = $sth->fetch()) 
            {
                do 
                {
                    $prev_serial=$data['serial_no'];

                } 
                while ($data = $sth->fetch());
            }
            $month_prev=substr($prev_serial,6,1);
            switch($month)
            {
                case '01':
                    if($month_prev=='A')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-A".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-A".$year."-".$last;      
                    }
                break;
                case '02':
                    if($month_prev=='B')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-B".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-B".$year."-".$last;  
                    }
                break;
                case '03':
                    if($month_prev=='C')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-C".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-C".$year."-".$last;  
                    }
                break;
                case '04':
                    if($month_prev=='D')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-D".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-D".$year."-".$last;  
                    }
                break;
                case '05':
                    if($month_prev=='E')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-E".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-E".$year."-".$last;  
                    }
                break;
                case '06':
                    if($month_prev=='F')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-F".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-F".$year."-".$last;  
                    }
                break;
                case '07':
                    if($month_prev=='G')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-G".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-G".$year."-".$last;  
                    }
                break;
                case '08':
                    if($month_prev=='H')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-H".$year."-".$last;
                    }
                    else
                    {
 
                        $last="101";
                        $serial="SCSPL-H".$year."-".$last;
 
                    }
                break;
                case '09':
                    if($month_prev=='I')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-I".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-I".$year."-".$last;  
                    }
                break;
                 case '10':
                    if($month_prev=='J')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-J".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-J".$year."-".$last;  
                    }
                break;
                case '11':
                    if($month_prev=='K')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-K".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-K".$year."-".$last;  
                    }
                break;
                case '12':
                    if($month_prev=='L')
                    {
                        $last=substr(strrchr($prev_serial, "-"), 1);
                        $last=$last+1;
                        $serial="SCSPL-L".$year."-".$last;
                    }
                    else
                    {
                        $last="101";
                        $serial="SCSPL-L".$year."-".$last;  
                    }
                break;
                default:
                break;
             }
            $date=date('Y-m-d');
            $used="no";
            $status="inactive";
            $reg_status="success";
            $s = $db->prepare("insert into admin_device(deviceid,serial_no,test_date,used,status,created_date) values('$deviceid','$serial','$date','$used','$status','$date')");
            $qry = $db->prepare("insert into quality_test(device,serial,test_date,reg_status) values('$deviceid','$serial','$date','$reg_status')");
            $qry->execute();
            if($s->execute())
            {
                $response = array('status' =>1,'serial'=>$serial,'mesage'=>"success");
                echo json_encode($response);
            }
            else
            {
                $response = array('status' =>2,'message'=>"fail to register" );
                echo json_encode($response);
            }
        }      
    }
 ?>