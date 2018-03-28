<?php
    include('../../connect.php');
    if(is_ajax())
    {
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $english=$_POST['english'];
        $bangla=$_POST['bangla'];
        $gujarati=$_POST['gujarati'];
        $hindi=$_POST['hindi'];
        $kannada=$_POST['kannada'];
        $malayalam=$_POST['malayalam'];
        $marathi=$_POST['marathi'];
        $punjabi=$_POST['punjabi'];
        $tamil=$_POST['tamil'];
        $telugu=$_POST['telugu'];$status="active";
        for($count = 0; $count<count($english); $count++)
        {
            $english_clean = $english[$count]; 
            $bangla_clean = $bangla[$count]; 
            $gujarati_clean = $gujarati[$count]; 
            $hindi_clean = $hindi[$count]; 
            $kannada_clean = $kannada[$count]; 
            $malayalam_clean = $malayalam[$count]; 
            $marathi_clean = $marathi[$count]; 
            $punjabi_clean = $punjabi[$count]; 
            $tamil_clean = $tamil[$count]; 
            $telugu_clean = $telugu[$count]; 
            if($english_clean==""  && $bangla_clean=="" && $gujarati_clean=="" && $hindi_clean=="" && $kannada_clean=="" && $malayalam_clean=="" && $marathi_clean=="" && $punjabi_clean=="" && $tamil_clean=="" && $telugu_clean=="")
            {
            }
            else
            {
                $query=$db->prepare("insert into product_mst (English, Bengali, Gujarati, Hindi, Kannada, Malayalam, Marathi, Punjabi, Tamil, Telugu, status, created_by_date, created_by_id) values('$english_clean', '$bangla_clean','$gujarati_clean', '$hindi_clean','$kannada_clean', '$malayalam_clean', '$marathi_clean', '$punjabi_clean', '$tamil_clean', '$telugu_clean', '$status' , '$date', '$id')");
                $query->execute();
                $sk=$query->rowCount();
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