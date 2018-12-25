<?php
    session_start();
    include ('../connect.php');
    require_once('../array_to_json.php');

    $d_id=$_POST['d_id'];
    $page_no=$_POST['page_no'];
    $next=$page_no+1;

    $status='active';
    $limit=50;
    $start_from = ($page_no-1) * $limit;
    $product_query=$db->prepare("select distinct bill_no, transaction_id, bill_amt, tax_amt, discount,parcel_amt, if(tax_state=0,parcel_amt+tax_amt+bill_amt-discount,parcel_amt+bill_amt-discount) as net, bill_date from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' LIMIT $start_from, $limit");
    $product_query->execute();
        $arr=array();
        if($data=$product_query->fetch(PDO::FETCH_ASSOC))
        {
            do
            {
                $transaction_id=$data['transaction_id'];
                $count_query=$db->prepare("select item_name as english_name, quantity, price, total_amt from transaction_dtl where transaction_dtl.transaction_id='$transaction_id'");
                $count_query->execute();
                $count_data=$count_query->fetchAll(PDO::FETCH_ASSOC);
                array_push($data,$count_data);
                array_push($arr, $data);
            }
            while($data=$product_query->fetch(PDO::FETCH_ASSOC));
        } 
        $data_array = array('page'=>$next,'data'=>$arr);
        $response=array_to_json($data_array);

    $status='active';
   
    echo $response; 
?>
