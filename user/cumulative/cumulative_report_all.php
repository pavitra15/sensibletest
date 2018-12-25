<?php
	include('../connect.php');
?>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="header">
                        <h2>DEVICE WISE SALE REPORT</h2>
                    </div>
                    <div class="body">
                        <?php
                        $id=$_POST['id'];
                      
                        $cat="[
                            ['Device', 'Sale']";          
                            $category_query=$db->prepare("select d_id, device_name, sum(transaction_mst.bill_amt) as total from device, transaction_mst where transaction_mst.device_id=device.d_id and  device.id='$id' group by(device.d_id)");
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