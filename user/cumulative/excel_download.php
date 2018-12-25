<?php
    session_start();
    include('../validate.php');   
?>
<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://www.igniteui.com/js/external/FileSaver.js"></script>
    <script src="https://www.igniteui.com/js/external/Blob.js"></script>
	<script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/infragistics.core.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_core.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_collections.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_text.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_io.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_ui.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.documents.core_core.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_collectionsextended.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.excel_core.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_threading.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_web.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.xml.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.documents.core_openxml.js"></script>
    <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.excel_serialization_openxml.js"></script>
</head>
<body>
	<?php
		$d_id=$_SESSION['d_id'];
		$status='active';
		$category_query=$db->prepare("select category_name from category_dtl where deviceid='$d_id' and status='$status'");
	   	$category_query->execute();
	    $category_data=$category_query->fetchAll(PDO::FETCH_COLUMN, 0);

        $tax_query=$db->prepare("select tax_name from tax_mst where status='$status'");
        $tax_query->execute();
        $tax_data=$tax_query->fetchAll(PDO::FETCH_COLUMN, 0);

        if($_SESSION['device_type']=="Table")
        {
            $kitchen_query=$db->prepare("select kitchen_name from kitchen_dtl where deviceid='$d_id' and status='$status'");
            $kitchen_query->execute();
            $kitchen_data=$kitchen_query->fetchAll(PDO::FETCH_COLUMN, 0);
        }

        $headers=array('English Name','Regional Name','Barcode');
        if($_SESSION['device_type']=="Weighing")
        {
            array_push($headers, 'Weightable');
            array_push($headers, 'Category');
            array_push($headers, 'Discount');
            array_push($headers, 'Unit');
            array_push($headers, 'Stockable');
            array_push($headers, 'Reorder Level');
            array_push($headers, 'Stock');
            array_push($headers, 'Tax Type');
            $sk=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
            $sk->execute();
            if($data=$sk->fetch())
            {
                do
                {
                    array_push($headers, $data['customer_name'].' Price');
                }
                while($data=$sk->fetch());
            } 
        }
        elseif ($_SESSION['device_type']=="Table") 
        {
            array_push($headers, 'Category');
            array_push($headers, 'Kitchen');
            array_push($headers, 'Discount');
            array_push($headers, 'Unit');
            array_push($headers, 'Stockable');
            array_push($headers, 'Reorder Level');
            array_push($headers, 'Stock');
            array_push($headers, 'Tax Type');
            array_push($headers, 'Parcel Price');
            $sk=$db->prepare("select * from premise_dtl where deviceid='$d_id' and status='$status'");
            $sk->execute();
            if($data=$sk->fetch())
            {
                do
                {
                    array_push($headers, $data['premise_name'].' Price');
                }
                while($data=$sk->fetch());
            }
        }
        else
        {
            array_push($headers, 'Category');
            array_push($headers, 'Discount');
            array_push($headers, 'Unit');
            array_push($headers, 'Stockable');
            array_push($headers, 'Reorder Level');
            array_push($headers, 'Stock');
            array_push($headers, 'Tax Type');
            array_push($headers, 'Default Price');  
            $sk=$db->prepare("select * from customer_dtl where deviceid='$d_id' and status='$status'");
            $sk->execute();
            if($data=$sk->fetch())
            {
                do
                {
                    array_push($headers, $data['customer_name'].' Price');
                }
                while($data=$sk->fetch());
            }   
        }
	?>
    <button id="exportButton" onclick="createFormattingWorkbook()">Create File</button>
    <script>	
        function createFormattingWorkbook() 
        {
            var headers=<?php echo json_encode($headers); ?>;
        	var category=<?php echo json_encode($category_data); ?>;
            var tax=<?php echo json_encode($tax_data); ?>;
            var device_type='<?php echo $_SESSION['device_type']; ?>';
            var workbook = new $.ig.excel.Workbook($.ig.excel.WorkbookFormat.excel2007);
            var instruction = workbook.worksheets().add('Instruction');
            instruction.rows(0).cellFormat().font().bold(true);
            instruction.rows(8).cellFormat().font().bold(true);
            instruction.getCell('A1').value("Note : ");
            var mergedCellB2F2 = instruction.mergedCellsRegions().add(1, 1, 1, 5);
            var mergedCellB3F3 = instruction.mergedCellsRegions().add(2, 1, 2, 5);
            var mergedCellB4F4 = instruction.mergedCellsRegions().add(3, 1, 3, 5);
            mergedCellB2F2.value("1. Please insert correct data.");
            mergedCellB3F3.value("2. Category name is same as in category column.");
            mergedCellB4F4.value("3. Tax name is same as in tax type column.");

            instruction.columns(1).setWidth(110, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            instruction.columns(2).setWidth(110, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            instruction.getCell('B9').value("Tax Name");
            for(var i=0;i<tax.length;i++)
            {
                var k=i+10;
                instruction.getCell('B'+k).value(tax[i]);
            }
            instruction.getCell('C9').value("Category");
            for(var i=0;i<category.length;i++)
            {
                var k=i+10;
                instruction.getCell('C'+k).value(category[i]);
            }
            if(device_type==="Table")
            {
                var kitchen=<?php echo json_encode($kitchen_data); ?>;
                var mergedCellB5F5 = instruction.mergedCellsRegions().add(4, 1, 4, 5);
                mergedCellB5F5.value("4. Kitchen name is same as in kitchen column.");

                instruction.getCell('D9').value("Kitchen");
                for(var i=0;i<kitchen.length;i++)
                {
                    var k=i+10;
                    instruction.getCell('D'+k).value(kitchen[i]);
                }
            }
            instruction.protect();


            var product = workbook.worksheets().add('Product');
            product.rows(0).cellFormat().font().bold(true);
            var char='A';
            
            for(var i=0;i<headers.length;i++)
            {
            	product.columns(i).setWidth(110, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            	product.getCell(char+'1').value(headers[i]);
            	char=String.fromCharCode(char.charCodeAt(0) + 1);
            }
            product.protect();
            for(var j=1; j<1000; j++)
            {
            	product.rows(j).cellFormat().locked(false);	
            }

            product.columns(4).cellFormat().formatString('$#,##0.00_);[Red]($#,##0.00)');
            // Save the workbook
            saveWorkbook(workbook, "Product.xlsx");
        }

        function saveWorkbook(workbook, name) {
            workbook.save({ type: 'blob' }, function (data) {
                saveAs(data, name);
            }, function (error) {
                alert('Error exporting: : ' + error);
            });
        }

    </script>
</body>
</html>