<?php
	include('../connect.php');

    $id=$_POST['id'];
    $first_date =  new DateTime($_POST['start_date']);
    $second_date = new DateTime($_POST['end_date']);
    $clone_first=clone $first_date;
    $clone_second=clone $second_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    $end_date = $second_date->format('Y-m-d 23:59:59');

    $report_date_first=$clone_first->format('l jS \of F Y');
    $report_date_second=$clone_second->format('l jS \of F Y');

    $name="...";
    $user_name="...";
    $checkout=0;
    $device_name="";
    $status='active';
    $checkout_query=$db->prepare("select sum(bill_amt) as total, count(bill_no) as count from transaction_mst, device where transaction_mst.device_id=device.d_id and transaction_mst.status='$status' and device.id='$id' and bill_date between '$start_date' and '$end_date'");
    $checkout_query->execute();
    while($data=$checkout_query->fetch())
    {
        $total=$data['total'];
        $count=$data['count'];
    }
    if($count!=0)
    {
        $checkout=$total/$count;
    }
    $top_query=$db->prepare("select english_name, sum(transaction_dtl.quantity) as count from transaction_dtl,product, transaction_mst, device where transaction_dtl.item_id=product.product_id and transaction_mst.device_id=device.d_id and transaction_dtl.transaction_id=transaction_mst.transaction_id  and transaction_mst.status='$status' and device.id='$id' and bill_date between '$start_date' and '$end_date' group by item_id Order by sum(transaction_dtl.quantity) desc limit 1");
    $top_query->execute();
    while($data_top=$top_query->fetch())
    {
        $name=$data_top['english_name'];
    }
    $user_query=$db->prepare("select device_name, count(transaction_mst.device_id) as count from device, transaction_mst where transaction_mst.device_id=device.d_id and device.id='$id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' group by transaction_mst.device_id Order by count(transaction_mst.device_id) desc limit 1");
    $user_query->execute();
    while($data=$user_query->fetch())
    {
        $device_name=$data['device_name'];
    }

    if(strlen($name) >16)
    {
        $name=substr($name, 0,14);
        $name=$name.'...';   
    }

    if(strlen($user_name) >16)
    {
        $device_name=substr($device_name, 0,14);
        $device_name=$device_name.'...';   
    }
?>
            <!-- Widgets -->
            <div class="block-header">
                <h2><?php 
                        if($_POST['report'] == "TODAY : ")
                        {
                            echo $_POST['report']." ".$report_date_first;
                        }
                        else
                        {
                            echo $_POST['report']." ".$report_date_first." - ".$report_date_second;
                        }
                        ?></h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-indigo">insert_chart</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Sales</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">Top Sale Item</div>
                            <div class="number"><?php echo $name; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-4">
                        <div class="icon">
                            <i class="material-icons col-red">shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">Best Store</div>
                            <div class="number"><?php echo $device_name; ?></div>
                        </div>
                    </div>
                </div>
            </div>

<div class="row clearfix">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="header">
                        <h2>DEVICE WISE SALE REPORT</h2>
                    </div>
                    <div class="body">
                        <?php
                        
                        $cat="[
                            ['Device', 'Sale']";          
                            $category_query=$db->prepare("select d_id, device_name, sum(transaction_mst.bill_amt) as total from device, transaction_mst where transaction_mst.device_id=device.d_id and  device.id='$id' and bill_date between '$start_date' and '$end_date' group by(device.d_id) ");
                            $category_query->execute();
                            while ($category_data=$category_query->fetch()) 
                            {   
                                $category=$category_data['total'];
                                $category_name=$category_data['device_name'];
                                if($category=="")
                                {
                                    $category=0;
                                }
                                $cat.=",['".$category_name."', ".$category." ]";
                            }
                        $cat.="]";
                    ?>
                        <div id="piechar" style="height: 400px;">
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                 google.charts.setOnLoadCallback(drawChart);

                                  function drawChart() {

                                    var data = google.visualization.arrayToDataTable(<?php echo $cat;?>);

                                    var options = {
                                    };

                                    var chart = new google.visualization.PieChart(document.getElementById('piechar'));

                                    chart.draw(data, options);
                                  }
                            </script>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="header">
                        <h2>SALE REPORT</h2>
                    </div>
                    <div class="body">
                    </div>
                </div>
            </div>