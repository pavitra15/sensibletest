<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<?php
	session_start();
	include('../connect.php');
	$d_id=$_POST['d_id'];
	$type_query=$db->prepare("select device_type from device where d_id='$d_id'");
	$type_query->execute();
	while($type_data=$type_query->fetch())
	{
		$device_type=$type_data['device_type'];
	}
	$_SESSION['from_type']=$device_type;
	if($device_type=="Table")
	{
?>
		<div class="col-sm-6">
		 	<p>
	        	<b>Premise Type</b>
	        </p>
	        <div class="demo-checkbox">
	        	<input type="checkbox" id="premise" class="filled-in" checked/>
	            <label for="premise">Premise Setting</label>
	        </div>
<?php
		$premise_query=$db->prepare("select premise_id, premise_name from premise_dtl where deviceid='$d_id' and status='active'");
		$premise_query->execute();
		if($premise_data=$premise_query->fetch())
		{
			echo'<div class="demo-checkbox" style="padding-left: 30px">
				<input type="checkbox" id="pre_all" class="filled-in" value="" />
				<label for="pre_all">Select All</label>
			</div>';
			do
			{
				echo'<div class="demo-checkbox" style="padding-left: 30px">
					<input type="checkbox" id="'.$premise_data['premise_name'].'" class="filled-in premise" value="'.$premise_data['premise_id'].'" />
		           	<label for="'.$premise_data['premise_name'].'">'.$premise_data['premise_name'].'</label>
			    </div>';
			}
			while($premise_data=$premise_query->fetch());
		}
		else
		{
			echo "<label>'No record to select</label>";
		}
		echo "</div>";
	}
	else
	{
?>
		<div class="col-sm-6">
		 	<p>
	        	<b>Bill Type</b>
	        </p>
	        <div class="demo-checkbox">
	        	<input type="checkbox" id="premise" class="filled-in" checked/>
	            <label for="premise">Bill Type</label>
	        </div>
<?php
		$premise_query=$db->prepare("select customer_id, customer_name from customer_dtl where deviceid='$d_id' and status='active'");
		$premise_query->execute();
		if($premise_data=$premise_query->fetch())
		{
			echo'<div class="demo-checkbox" style="padding-left: 30px">
				<input type="checkbox" id="pre_all" class="filled-in" value=""/>
				<label for="pre_all">Select All</label>
			</div>';
			do
			{
				echo'<div class="demo-checkbox" style="padding-left: 30px">
					<input type="checkbox" id="'.$premise_data['customer_name'].'" class="filled-in premise" value="'.$premise_data['customer_id'].'" />
		           	<label for="'.$premise_data['customer_name'].'">'.$premise_data['customer_name'].'</label>
			    </div>';
			}
			while($premise_data=$premise_query->fetch());
		}
		else
		{
			echo "<label>'No record to select</label>";
		}
		echo "</div>";
	}
?>

	</div>
    <div class="col-sm-6">
	 	<p>
        	<b>Category</b>
        </p>
		<div class="demo-checkbox">
		 	<input type="checkbox" id="category" class="filled-in" checked />
			<label for="category">Category</label>
			<?php
				$category_query=$db->prepare("select category_id,category_name from category_dtl where deviceid='$d_id' and status='active'");
				$category_query->execute();
				if($category_data=$category_query->fetch())
				{
					echo'<div class="demo-checkbox" style="padding-left: 30px">
							<input type="checkbox" id="cat_all" class="filled-in" value=""/>
				           	<label for="cat_all">Select All </label>
					    </div>';
					do
					{
						echo'<div class="demo-checkbox" style="padding-left: 30px">
							<input type="checkbox" id="'.$category_data['category_name'].'" class="filled-in category" value="'.$category_data['category_id'].'"/>
				           	<label for="'.$category_data['category_name'].'">'.$category_data['category_name'].'</label>
					    </div>';
					}
					while($category_data=$category_query->fetch());
				}
				else
				{
					echo "<label>'No record to select</label>";
				}
		echo "</div>";

			?>
        </div>
    </div>
</div>
<div class="row clearfix">
	<div class="col-sm-12">
	 	<p>
        	<b>Product</b>
        </p>
        <div class="demo-checkbox">
			<input type="checkbox" id="product" class="filled-in" checked />
            <label for="product">Product</label>
        </div>
        <!-- <div class="demo-checkbox" style="padding-left: 30px">
			<input type="checkbox" id="pro_all" class="filled-in" value=""/>
			<label for="pro_all">Select All </label>
		</div> -->
<table class="table table-bordered table-striped table-hover dataTable js-exportable" cellspacing="0" width="100%" id="example">
<thead>
            <tr>
                <th style='display:none;'>id</th>
                <th>Name</th>
                <th>Regional Name</th>
                <th>Category Name</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th style='display:none;'>id</th>
                <th>Name</th>
                <th>Regional Name</th>
                <th>Category Name</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
                $query=$db->prepare("select product_id, english_name, regional_name,category_name from product,category_dtl where category_dtl.category_id=product.category_id and product.deviceid='$d_id' and product.status='active'");
                $query->execute();
                if($data=$query->fetch())
                {
                    do
                    {
                        echo"<tr>
                            <td style='display:none;'>".$data['product_id']."</td>
                            <td>".$data['english_name']."</td>
                            <td>".$data['regional_name']."</td>
                            <td>".$data['category_name']."</td>
                        </tr>";
                    }
                    while ($data=$query->fetch());
                }
            ?>
        </tbody>
    </table>
         
    <script type="text/javascript">
   		$("#cust_all").change(function(){  //"select all" change 
    		$(".customer").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
		});

		$("#pre_all").change(function(){  //"select all" change 
		    $(".premise").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
		});

			//".checkbox" change 
		$('.premise').change(function(){ 
		    //uncheck "select all", if one of the listed checkbox item is unchecked
		    if(false == $(this).prop("checked")){ //if this item is unchecked
		        $("#pre_all").prop('checked', false); //change "select all" checked status to false
		    }
		    //check "select all" if all checkbox items are checked
		    if ($('.premise:checked').length == $('.premise').length ){
		        $("#pre_all").prop('checked', true);
		    }
		});


		$("#cat_all").change(function(){  //"select all" change 
		    $(".category").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
		});

		$("#prod_all").change(function(){  //"select all" change 
		    $('.tr_select').toggleClass('selected');
		});
		//".checkbox" change 
		$('.category').change(function(){ 
		    //uncheck "select all", if one of the listed checkbox item is unchecked
		    if(false == $(this).prop("checked")){ //if this item is unchecked
		        $("#cat_all").prop('checked', false); //change "select all" checked status to false
		    }
		    if ($('.category:checked').length == $('.category').length ){
		        $("#cat_all").prop('checked', true);
		    }
		});

   </script>
	<script src="../plugins/jquery-datatable/jquery.dataTables.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
var products = [];
$(document).ready(function() {
    var table = $('.table').DataTable( {
        dom: 'Bfrtip',
        select: {
     style: 'multi'
  }
    } );
} );

$(document).ready(function() {
    var table = $('#example').DataTable();
    $('#example tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
            products.length=0;
            var oData = table.rows('.selected').data();
            for (var i=0; i < oData.length ;i++){
                     products.push(oData[i][0]);
                }
    } );
    } );
    </script>

