<?php
session_start();
include('../connect.php');
    if($_POST['user_search'])
    {
        $username=$_POST['user_search']; 
        $status="active";
        $user_query=$db->prepare("select * from login_mst,user_mst where user_mst.id=login_mst.id and username='$username' and login_mst.status='$status'");
        $user_query->execute();
        $count=$user_query->rowCount();
        if($count>0)
        {
            if($user_data=$user_query->fetch())
            {
                do
                {
                    $_SESSION['new_mobi']=$user_data['mobile'];
                     $_SESSION['new_id']=$user_data['id'];
                    echo'<div class="col-md-4">
                        <div class="form-group">
                            <label> First Name:</label>
                        </div>
                        <div class="form-group">
                            <label> Last Name:</label>
                        </div>
                        <div class="form-group">
                            <label> Mobile:</label>
                        </div>      
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>'. $user_data['first_name'].'</label>
                        </div>
                         <div class="form-group">
                            <label>'.$user_data['last_name'].'</label>
                        </div>
                         <div class="form-group">
                            <label>'. $user_data['mobile'].'</label>
                        </div>
                    </div>
                    <button class="btn btn-primary waves-effect" name="generate" type="submit">GENERATE OTP</button>';    

                }
                while($user_data=$user_query->fetch());
            }
        }
        else
        {
            $_SESSION['new_mobi']="";
            $_SESSION['new_id']="";
            echo'<div class="form-group">
                <label>Sorry ! New user not found. please signup new user. visit us https://app.sensibleconnect.com</label>
            </div>';
        }
    }
?>