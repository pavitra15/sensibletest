<?php
    include('../connect.php');
    $request = $_REQUEST["data"];
    $jsonData = json_decode($request,true);
    foreach($jsonData as $key => $value)
    {
        $requestID = $value['requestId'];
        $userId = $value['userId'];
        $senderId = $value['senderId'];
        foreach($value['report'] as $key1 => $value1)
        {
            $desc = $value1['desc'];
            $status = $value1['status'];
            $receiver = $value1['number'];
            $date = $value1['date'];
            $query=$db->prepare("insert into sms_delivery (request_id,user_id,sender_id,date,receiver,status,description) values('$requestID','$userId','$senderId','$date','$receiver','$status','$desc')");
            echo"insert into sms_delivery (request_id,user_id,sender_id,date,receiver,status,description) values('$requestID','$userId','$senderId','$date','$receiver','$status','$desc')";
            $query->execute();
        }
    }
?>