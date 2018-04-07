<?php
    include('../connect.php');
    $deviceid=$_POST['deviceid'];
    $d_id=$_POST['d_id'];
    $token=$_POST['token'];
    $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=2b3d7d0ad1a285279139487ce77f3f58d980eea9546b5ccc5d08f5ee62ce7471&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
    $country=$url->countryName;
    $city=$url->cityName;
    $state=$url->regionName;
    $ip=$url->ipAddress;
    $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
    $last_login=$date->format('Y-m-d H:i:s');
    $insert_query=$db->prepare("insert into last_connect_admin(deviceid,city,state, country, ip, last_login)values('$deviceid','$city','$state','$country','$ip','$last_login') ON DUPLICATE KEY UPDATE city='$city', state='$state', country='$country', ip='$ip', last_login='$last_login'");
    $insert_query->execute();
    $status="active";
    $query=$db->prepare("select * from mobile_token_verify where token='$token' and deviceid='$deviceid' and status='$status'");
    $query->execute();
    $token_count=$query->rowCount();
    if($token_count==1)
    {
        $product_query=$db->prepare("select customer_id, customer_name, customer_contact from  customer_mst where customer_mst.deviceid='$d_id'");
        $product_query->execute();
        $response='{"status":1,"customer_report":[';
        if($data=$product_query->fetch())
        {
            do
            {
                $customer_id=$data['customer_id'];
                $response.='{"customer_name":"'.$data['customer_name'].'","mobile":"'.$data['customer_contact'].'","detail":[';
                $count_query=$db->prepare("select bill_no, bill_date, bill_amt, cash, credit, digital from transaction_mst where transaction_mst.customer_id='$customer_id'");
                $count_query->execute();
                if($row_cnt = $count_query->fetch())
                {
                    do
                    {
                        $response.='{"bill_no":"'.$row_cnt['bill_no'].'","bill_date":"'.$row_cnt['bill_date'].'","bill_amt":'.$row_cnt['bill_amt'].',"cash":'.$row_cnt['cash'].',"credit":'.$row_cnt['credit'].',"digital":'.$row_cnt['digital'].'},';
                    }
                    while ($row_cnt = $count_query->fetch());
                    $response = rtrim($response, ',');
                    $response.=']},';
                }
                else
                {
                    $response.=']},';
                }       
            }
            while($data=$product_query->fetch());
            $response = rtrim($response, ',');
            $response.=']}';
        }
        else
        {
            $response.=']}';
        }
        echo $response;
    }
    else
    {
        $responce = array('status' =>0,'message'=>"Token mismatch");
        echo json_encode($responce);
    }
?>