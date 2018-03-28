<<?php
    include('super_admin_verify.php');
?> 

<?
    if(isset($_POST['upload']))
    {

        $flag=5;
        $date=date('Y-m-d');
        $id=$_SESSION['login_id'];
        $version=$_POST['version'];
        $name="POSiBILL".".apk";
        $target_dir = "gs://glass-approach-179716.appspot.com/apk_store/";
        $target_file = $target_dir.$name;
        $uploadOk = 1;
        $st=$db->prepare("select paths,version from apk where apk_id=1");
        $st->execute();
        if ($data = $st->fetch()) 
        {
            do 
            {
                $path=$data['paths'];
            }
            while($data = $st->fetch());
            if (file_exists($path)) 
            {
                unlink($path);
            }
            if ($_FILES["file"]["size"] > 50000000) 
            {
                $uploadOk = 0;
            }
            if ($uploadOk == 0) 
            {
                $flag=4;
            } 
            else 
            {
               move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
                    $sth = $db->prepare("update apk set name='$name', version='$version', paths='$target_file', updated_by_id=$id, updated_by_date='$date', version=$version where apk_id=1");
                    if($sth->execute())
                    {
                
                        $_SESSION['apk']=2;
                        echo '<script >window.location="../super_admin/upload.php";</script>';
                    }
                    else
                    { 
                    }
            }
        }
        else 
        {
            echo "ffuklfrekl";
            $_SESSION['apk']=3;
        }
    }
?>
