<?php
    session_start();
    include('connect.php');
    // include('validate.php');   
	require 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

	$d_id=$_SESSION['d_id'];
	$status='active';
	$category_query=$db->prepare("select category_name from category_dtl where deviceid='$d_id' and status='$status'");
   	$category_query->execute();
    $category_data=$category_query->fetchAll(PDO::FETCH_COLUMN, 0);
    $category='"'.implode(",",$category_data).'"';

    $unit_query=$db->prepare("select unit_name from unit_mst where status='$status'");
   	$unit_query->execute();
    $unit_data=$unit_query->fetchAll(PDO::FETCH_COLUMN, 0);
    $unit='"'.implode(",",$unit_data).'"';


    $tax_query=$db->prepare("select tax_name from tax_mst where status='$status'");
   	$tax_query->execute();
    $tax_data=$tax_query->fetchAll(PDO::FETCH_COLUMN, 0);
    $tax='"'.implode(",",$tax_data).'"';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
		
	$headers=array('English Name','Regional Name');

	$yes_list='"Yes,No"';

	if($_SESSION['device_type']=="Weighing")
    {
    	$spreadsheet->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
    	array_push($headers, 'Weightable');
    	array_push($headers, 'Category');
    	for($i=3;$i<1000;$i++)
		{
			$validation = $spreadsheet->getActiveSheet()->getCell('E'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
			$validation->setAllowBlank(true);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Number is not allowed!');
			$validation->setPromptTitle('Allowed input');
			$validation->setPrompt('Only numbers between 0 and 100 are allowed.');
			$validation->setFormula1(0);
			$validation->setFormula2(100);

			$validation = $spreadsheet->getActiveSheet()->getCell('C'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($yes_list);

			$validation = $spreadsheet->getActiveSheet()->getCell('D'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($category);


			$validation = $spreadsheet->getActiveSheet()->getCell('F'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($unit);

			$validation = $spreadsheet->getActiveSheet()->getCell('G'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($yes_list);

			$validation = $spreadsheet->getActiveSheet()->getCell('I'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
			$validation->setAllowBlank(true);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Number is not allowed!');
			$validation->setPromptTitle('Allowed input');
			$validation->setPrompt('Only numbers are allowed.');
			$validation->setFormula1(0);
			$validation->setFormula2(99999999);

			$validation = $spreadsheet->getActiveSheet()->getCell('J'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
			$validation->setAllowBlank(true);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Number is not allowed!');
			$validation->setPromptTitle('Allowed input');
			$validation->setPrompt('Only numbers are allowed.');
			$validation->setFormula1(0);
			$validation->setFormula2(99999999);

			$validation = $spreadsheet->getActiveSheet()->getCell('J'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($tax);
		}
   	}
    else
    {
    	array_push($headers, 'Category');
       	$spreadsheet->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);

        for($i=3;$i<1000;$i++)
		{
			$validation = $spreadsheet->getActiveSheet()->getCell('C'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($category);

			$validation = $spreadsheet->getActiveSheet()->getCell('D'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
			$validation->setAllowBlank(true);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Number is not allowed!');
			$validation->setPromptTitle('Allowed input');
			$validation->setPrompt('Only numbers between 0 and 100 are allowed.');
			$validation->setFormula1(0);
			$validation->setFormula2(100);


			$validation = $spreadsheet->getActiveSheet()->getCell('E'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($unit);

			$validation = $spreadsheet->getActiveSheet()->getCell('F'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($yes_list);

			$validation = $spreadsheet->getActiveSheet()->getCell('G'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
			$validation->setAllowBlank(true);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Number is not allowed!');
			$validation->setPromptTitle('Allowed input');
			$validation->setPrompt('Only numbers are allowed.');
			$validation->setFormula1(0);
			$validation->setFormula2(99999999);

			$validation = $spreadsheet->getActiveSheet()->getCell('H'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
			$validation->setAllowBlank(true);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Number is not allowed!');
			$validation->setPromptTitle('Allowed input');
			$validation->setPrompt('Only numbers are allowed.');
			$validation->setFormula1(0);
			$validation->setFormula2(99999999);


			$validation = $spreadsheet->getActiveSheet()->getCell('I'.$i)
			    ->getDataValidation();
			$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
			$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
			$validation->setAllowBlank(false);
			$validation->setShowInputMessage(true);
			$validation->setShowErrorMessage(true);
			$validation->setShowDropDown(true);
			$validation->setErrorTitle('Input error');
			$validation->setError('Value is not in list.');
			$validation->setPromptTitle('Pick from list');
			$validation->setPrompt('Please pick a value from the drop-down list.');
			$validation->setFormula1($tax);
		}
    }
    array_push($headers, 'Discount');
    array_push($headers, 'Unit');
    array_push($headers, 'Stockable');
    array_push($headers, 'Reorder Level');
    array_push($headers, 'Stock');
    array_push($headers, 'Tax Type');

	if($_SESSION['device_type']=="Table")
    {
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

		$char='A';
        for($i=0;$i<sizeof($headers);$i++)
        {
        	if(($headers[$i]=="Default Price")||($headers[$i]=="Parcel Price"))
        	{
        		$control_char=$char;
        		$control_start=$i;
        	}
            $sheet->setCellValue($char.'2',$headers[$i]);
            $char++;
        }




        for($j=$control_start;$j<sizeof($headers);$j++)
		{
			$spreadsheet->getActiveSheet()->getStyle($control_char)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
			for($i=3;$i<1000;$i++)
			{
				$validation = $spreadsheet->getActiveSheet()->getCell($control_char.$i)
				    ->getDataValidation();
				$validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL );
				$validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
				$validation->setAllowBlank(true);
				$validation->setShowInputMessage(true);
				$validation->setShowErrorMessage(true);
				$validation->setErrorTitle('Input error');
				$validation->setError('Number is not allowed!');
				$validation->setPromptTitle('Allowed input');
				$validation->setPrompt('Only numbers are allowed.');
				$validation->setFormula1(0);
				$validation->setFormula2(99999999);
			}
			$control_char++;
		}

		$sheet->getStyle('A2:Z2')->applyFromArray(
	   		array(
		  		'font'  => array(
			  	'bold'  =>  true
		  		)
	   		)
	 	);


        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getStyle('A3:Z1000')
    ->getProtection()
    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);



    foreach (range('A','V') as $col) {
  	$sheet->getColumnDimension($col)->setAutoSize(true);  
}

var_dump($spreadsheet);
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

header('Content-Disposition: attachment;filename="file.xlsx"');

header('Cache-Control: max-age=0');

$writer->save('php://output');

  //       $writer = new Xlsx($spreadsheet);
  //       $filename = 'product.xlsx';
		// $writer->save($filename);
