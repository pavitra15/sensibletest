<?php
	include('../connect.php');
	$deviceid=$_POST['deviceid'];
	$title=$_POST['title'];
    $sub_title=$_POST['sub_title'];
    $address=$_POST['address'];
    $contact=$_POST['contact'];
    $gstn=$_POST['gstn'];
    $bill_name=$_POST['bill_name'];
    $tax_invoice=$_POST['tax_invoice'];
    $sr_no=$_POST['sr_no'];
    $bill_no=$_POST['bill_no'];
    $sr_col=$_POST['sr_col'];
    $base_col=$_POST['base_col'];
    $disc_col=$_POST['disc_col'];
    $bill_time=$_POST['bill_time'];
    $footer=$_POST['footer'];
    $consolidated_tax=$_POST['consolidated_tax'];
    $payment_mode=$_POST['payment_mode'];
    $decimal_point=$_POST['decimal_point'];

	$query=$db->prepare("update print_setup set title='$title', sub_title='$sub_title', address='$address', contact='$contact', gstn='$gstn', tax_invoice='$tax_invoice', bill_name='$bill_name', sr_no='$sr_no', prnt_bill_no='$bill_no', prnt_sr_col='$sr_col', prnt_base_col='$base_col', prnt_bill_time='$bill_time',prnt_disc_col='$disc_col',footer='$footer', consolidated_tax='$consolidated_tax', payment_mode='$payment_mode', decimal_point='$decimal_point' where d_id='$deviceid'");
	if($query->execute())
    {
        echo "1";
    }
?>