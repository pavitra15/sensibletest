<?php
    include('../connect.php');
    if(is_ajax())
    {
        $rowcount=0;
        $status="active";
        $d_id=$_POST['d_id'];
        $date=date('Y-m-d');
        $id=$_POST['id'];
        $premise_name = $_POST['premise_name'];
        $table_no = $_POST['table_no'];
        $table_range = $_POST['table_range'];
        $premise_type = $_POST['premise_type'];
        for($count = 0; $count<count($premise_name); $count++)
        {
            $premise_name_clean = strtoupper(trim($premise_name[$count]));
            $table_no_clean =$table_no[$count];
            $table_range_clean =$table_range[$count];
            $premise_type_clean =$premise_type[$count];
            if($premise_name_clean=="" || $table_no_clean=="")
            {}
            else
            {
                $count_query=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='active'");
                $count_query->execute();
                $r_count=$count_query->rowcount();
                if($r_count<5)
                {
                    try
                    {
                        $select_query=$db->prepare("select premise_name from premise_dtl where premise_name='$premise_name_clean' and deviceid='$d_id' and status='active'");
                        $select_query->execute();
                        if($select_query->rowcount())
                        {

                        }
                        else
                        {
                            $db->beginTransaction();
                            $query=$db->prepare("insert into premise_dtl (premise_name, no_of_table, table_range,premise_type, deviceid, status, created_by_date, created_by_id) values('$premise_name_clean', '$table_no_clean', '$table_range_clean', '$premise_type_clean','$d_id', '$status' , '$date', '$id')");
                            $query->execute();
                            $db->commit();
                            $rowcount+=$query->rowcount();
                        }
                        
                    }catch (Exception $e) 
                    {
                        $db->rollBack();
                        // echo $e;
                    }
                   
                }
                else
                {
                    echo "2_";  
                }
            }
        }
        echo "1_".$rowcount;
    }
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
?>