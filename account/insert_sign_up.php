<?php
    session_start();
    include('../connect.php');
    if(is_ajax())
    {
        $username=$_POST['email'];
        $first_name=ucwords($_POST['first_name']);
        $last_name=ucwords($_POST['last_name']);
        $pass=$_POST['password'];
        $terms=$_POST['terms'];
        $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
        $clone= clone $date;
        $current_date=$clone->format( 'Y-m-d' ); 
        $user="user";
        $status="inactive";
        $gid="";
        $key='123acd1245120954';
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $pass, MCRYPT_MODE_ECB, $iv));

        $select_query=$db->prepare("select * from login_mst where username='$username'");
        $select_query->execute();
        $count=$select_query->rowCount();
        if ($count>0)
        {
            echo 1;  
        }
        else
        {
            if(is_numeric($username))
            {
                if(preg_match('/^[0-9]{10}+$/', $username))
                {
                    try
                    {
                        $db->beginTransaction();
                        $status='inactive';
                        
                        // $insert_query=$db->prepare("insert into temp_login (username,password,password_updated_date,status,type,created_by_date) values('$username',md5('$password'),'$current_date','$status','$user','$current_date')");

                        $insert_query=$db->prepare("insert into login_mst (username,password,password_updated_date,status,type,created_by_date) values('$username','$password','$current_date','$status','$user','$current_date')");

                        $insert_query->execute();
                        $count=$insert_query->rowCount();
                        if($count==1)
                        {
                            $_SESSION['mobile']=$username;
                            $query=$db->prepare("select id from login_mst where username='$username' and password='$password'");
                            $query->execute();
                            while ($login_data=$query->fetch()) 
                            {
                                $login_id=$login_data['id'];
                                $_SESSION['temp_id']=$login_data['id'];
                            }
                            $status='active';
                            $insert_query=$db->prepare("insert into user_mst(id, first_name,last_name, mobile, created_date,created_by_id ) values('$login_id','$first_name','$last_name','$username','$current_date','$login_id')");
                            $insert_query->execute();
                            ob_start();
                            system('ipconfig /all');
                            $mycom=ob_get_contents();
                            ob_clean();
                                     
                            $findme = "Physical";
                            $pmac = strpos($mycom, $findme);
                                     
                            $mac=substr($mycom,($pmac+36),17);
                            if(isset($terms))
                            {
                                $accept="Accept";
                                $term_query=$db->prepare("insert into termsndcon(login_id, mac_id, created_date, status)values('$login_id','$mac','$current_date','$accept')");
                                $term_query->execute();    
                            }
                            else
                            {
                                $accept="Decline";
                                $term_query=$db->prepare("insert into termsndcon(login_id, mac_id, created_date, status)values('$login_id','$mac','$current_date','$accept')");
                                $term_query->execute();    
                            }
                            $db->commit();
                            echo 2;
                        }
                    }
                    catch (Exception $e) 
                    {
                        $db->rollBack();
                        echo $e;
                        echo 3;
                    }
                }
                else
                {
                    echo 4;
                }
            }
            else
            {
                 echo 4;
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
