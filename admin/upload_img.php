<?php
    session_start();
    include('../connect.php');
    if (isset($_FILES['file']['name'])) 
    {
        $information=pathinfo($_FILES['file']['name']);
         
        if (0 < $_FILES['file']['error']) 
        {
            echo 'Error during file upload' . $_FILES['file']['error'];
        } 
        else 
        {
            if((strtolower($information['extension'])=="jpg") || (strtolower($information['extension'])=="png") || (strtolower($information['extension'])=="jpeg"))
            {
                $date=date('Y-m-d');
                $id=$_SESSION['login_id'];
                $product_id=$_POST['id'];
                $name=$product_id.".".$information['extension'];
                $target_dir = "gs://grounded-access-212210.appspot.com/image_lib/";
                $bucket = "https://storage.googleapis.com/nth-weft-193604.appspot.com/image_lib/".$name;
                $target_file = $target_dir.$name;
                $uploadOk = 1;
                if (file_exists($target_file)) 
                {
                    unlink($target_file);
                }
                if ($_FILES["file"]["size"] > 104000) 
                {
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) 
                {
                    echo 0;
                } 
                else 
                {
                    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
                    $sth = $db->prepare("update product_mst set paths='$target_file', bucket='$bucket', updated_by_id=$id, updated_by_date='$date' where product_id='$product_id'");
                    if($sth->execute())
                    {
                        echo 1;
                    }
                    else
                    { 
                    }
                }
            }
            else
            {
                echo 2;
            }
        }
    } 
    else 
    {
        echo 3;
    }
?>