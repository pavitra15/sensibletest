<?php    
    session_start();
    include('../connect.php');
    $dealer_id=$_POST['dealer_id'];
    $appstatus=$_POST['appstatus'];
    $remarks=$_POST['remarks'];   
    $date= new DateTime("now", new DateTimeZone('Asia/Kolkata'));
    $updated_by_date = $date->format('Y-m-d H:i:s');
    $updated_by_id   = $_SESSION['login_id'];
  
    $query=$db->prepare("update dealer_mst set appstatus='$appstatus',remarks='$remarks',updated_by_id='$updated_by_id',updated_by_date='$updated_by_date3e' where  dealer_id='$dealer_id'");
     if($query->execute())
    {
        echo json_encode(array('msg' => 'Successful'));
         
    }
    else
    {
         echo json_encode(array('msg' => 'Un successful'));
    }                
?>