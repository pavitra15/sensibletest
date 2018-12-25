<?php
    session_start();
    include('../connect.php');
    include ('../mailin-smtp-api-master/Mailin.php');
    $status='active';
    $message="";
    $page=$_GET['page'];
    $limit=40;
    $start_from = ($page-1) * $limit;
    $id_query=$db->prepare("select email,login_mst.id, first_name from login_mst,user_mst where login_mst.status='$status' and user_mst.id=login_mst.id LIMIT $start_from, $limit");
    $id_query->execute();
    while($id_data=$id_query->fetch())
    {
        $id=$id_data['id'];
        $first_name=$id_data['first_name'];
        $email=$id_data['email'];
        $deviceid_query=$db->prepare("select d_id, device_name from device where device.id='$id' and device.status='$status'");
        $deviceid_query->execute();
        $device_count=$deviceid_query->rowCount();
        $sk=1;
        if($device_count>0)
        {
            $last_date = new DateTime("1 days ago", new DateTimeZone('Asia/Kolkata') );
            $start_date = $last_date->format( 'Y-m-01 00:00:00');

            $end_date = $last_date->format( 'Y-m-d 23:59:59');
            $end_report_date=$last_date->format('l jS \of F Y');
            $message='<script src="https://use.fontawesome.com/f389b8ea61.js"></script><div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                        <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <a href="https://www.sensibleconnect.com"><img src="https://www.sensibleconnect.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle;">
                                        <span style="color: #a09bb9;">
                                            POSiBILL
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <div style="padding: 40px 40px 20px 40px; background: #fff;">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p>Hi '.$first_name.',</p>
                                                <p> This is monthly Summary report of your following devices.</p>
                                                ';
            while($deviceid_data=$deviceid_query->fetch())
            {
                $start_date = $last_date->format( 'Y-m-01 00:00:00');
                $end_date = $last_date->format( 'Y-m-d 23:59:59');
                $deviceid=$deviceid_data['d_id'];
                $device_name=$deviceid_data['device_name'];
                $status='active';
                $transaction_query=$db->prepare("select sum(bill_amt) as total_Sale, sum(tax_amt) as total_tax, count(bill_no) as bill_count from transaction_mst where device_id='$deviceid' and status='$status' and bill_date BETWEEN '$start_date' and '$end_date'");
                $transaction_query->execute();
                while ($transaction_data=$transaction_query->fetch())
                {
                    $total_Sale=$transaction_data['total_Sale'];
                    $total_tax=$transaction_data['total_tax'];
                    $bill_count=$transaction_data['bill_count'];
                }
                $top_query=$db->prepare("select english_name, sum(transaction_dtl.quantity) as count from transaction_dtl,product, transaction_mst where transaction_mst.bill_date BETWEEN '$start_date' and '$end_date' and transaction_dtl.item_id=product.product_id and device_id='$deviceid' and transaction_dtl.transaction_id=transaction_mst.transaction_id group by item_id Order by sum(transaction_dtl.quantity) desc limit 1");
                $top_query->execute();
                while($data_top=$top_query->fetch())
                {
                    $name=$data_top['english_name'];
                    $count=$data_top['count'];
                }
                if($bill_count==0)
                {
                    $message.='<h5 style="margin-bottom: 20px; color: #24222f; font-size:20px; font-weight: 600">Device Name : '.$device_name.'</h5>
                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                        <tr>
                                        <td style="text-align: center; padding: 20px 20px 20px 20px; border: 1px solid #514d6a;">
                                            No record Found
                                            </td>
                                        </tr>
                                    </table>';
                }
                else
                {
                    $avg_checkout=$total_Sale/$bill_count;
                    $message.='<h5 style="margin-bottom: 20px; font-size:20px; color: #24222f; font-weight: 600">Device Name : '.$device_name.'</h5>
                             <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td style="text-align: left; padding: 10px 10px 10px 0px; border-top: 3px solid #514d6a;">
                                        Daily Total Sale
                                    </td>
                                    <td style="width: 40%; text-align: center; padding: 10px 10px; border-top: 3px solid #514d6a;">
                                        <i class="fa fa-inr"></i> '.$total_Sale.'
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; padding: 10px 10px 10px 0px; border-top: 1px solid #514d6a;">
                                        Daily  Total Tax
                                    </td>
                                    <td style="width: 40%; text-align: center; padding: 10px 10px; border-top: 1px solid #514d6a;">
                                        <i class="fa fa-inr"></i> '.$total_tax.'
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; padding: 10px 10px 10px 0px; border-top: 1px solid #514d6a;">
                                        Top Sale Item
                                    </td>
                                    <td style="width: 40%; text-align: center; padding: 10px 10px; border-top: 1px solid #514d6a;">
                                        '.$name.'
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; padding: 10px 10px 10px 0px; border-top: 1px solid #514d6a; border-bottom: 3px solid #514d6a;">
                                        Average Check Value
                                    </td>
                                    <td style="width: 40%; text-align: center; padding: 10px 10px; border-top: 1px solid #514d6a; border-bottom: 3px solid #514d6a;">
                                        <i class="fa fa-inr"></i> '.round($avg_checkout,2).'
                                    </td>
                                </tr>
                            </table>';
                }
            }
            $message.='<p>Thank you,<br/>
                        The Sensible Connect Solutions Team</p>
                    </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align: center; font-size: 12px; color: #a09bb9; margin-top: 20px">
                                <p>
                                    Sensible Connect solutions Pvt Ltd, Pune, 411009
                                    <br />
                                    Dont like these emails? <a href="javascript: void(0);" style="color: #a09bb9; text-decoration: underline;">Unsubscribe</a>
                                    <br />
                                    © 2017 Sensible Connect solutions pvt Ltd. All Rights Reserved.
                                </p>
                            </div>
                        </div>
                    </div>';

                    // echo $message;

                            $mailin = new Mailin('info@sensibleconnect.com', 'QUT6g8qdZ7XmVn49');
                            $mailin->
                            addTo($email, 'Sensible Connect')->
                            setFrom('info@sensibleconnect.com', 'Sensible Connect')->
                            setReplyTo('info@sensibleconnect.com','Sensible Connect')->
                            setSubject('Daily Device Sale Summary')->
                            setText($message)->
                            setHtml($message);
                            $res = $mailin->send();
                    

        }
	}           
?>