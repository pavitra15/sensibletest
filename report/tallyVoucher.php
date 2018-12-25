<?php
	session_start();
	include('../connect.php');
	$d_id=$_POST['d_id'];
	$page_no=$_POST['page_no'];
	$next=$page_no+1;

	$first_date =  new DateTime($_POST['start_date']);
    $second_date = new DateTime($_POST['end_date']);
    $clone_first=clone $first_date;
    $clone_second=clone $second_date;
    $start_date = $clone_first->format('Y-m-d 00:00:00');
    $end_date = $second_date->format('Y-m-d 23:59:59');

    $status='active';
    $limit=30;
    $start_from = ($page_no-1) * $limit;
    $voucher="";

    $product_query=$db->prepare("select DISTINCT bill_no, bill_amt, bill_date from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date'");
    $product_query->execute();
    $product_count=$product_query->rowCount();

    $product_count=$product_count/30;

    $Product_query=$db->prepare("select DISTINCT bill_no, bill_amt, tax_state, discount, parcel_amt, tax_amt,parcel_amt, bill_date from  transaction_mst where transaction_mst.device_id='$d_id' and transaction_mst.status='$status' and bill_date between '$start_date' and '$end_date' LIMIT $start_from, $limit");  
    $Product_query->execute();

    if($row= $Product_query->fetch())
    {
    	do{
    		$flag1=0;
    		$flag2=0;
    		$flag3=0;
    		$flag4=0;
    		$flag5=0;
    		$sgst0=0;
    		$cgst0=0;
    		$sgst5=0;
    		$cgst5=0;
    		$sgst12=0;
    		$cgst12=0;
    		$sgst18=0;
    		$cgst18=0;
    		$sgst28=0;
    		$cgst28=0;
    		$date = new DateTime($row['bill_date']);
         	$visit_date=$date->format('Ymd');
    		$voucher.='<TALLYMESSAGE xmlns:UDF="TallyUDF">'.
					'<VOUCHER VCHTYPE="Sales" ACTION="Create" OBJVIEW="Invoice Voucher View">'.
				 		'<OLDAUDITENTRYIDS.LIST TYPE="Number">'.
				  			'<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>'.
				 		'</OLDAUDITENTRYIDS.LIST>'.
				 		'<DATE>'.$visit_date.'</DATE>'.
				 		'<GUID>'.$row['bill_no'].'</GUID>'.
				 		'<PARTYNAME>Cash</PARTYNAME>'.
				 		'<VOUCHERTYPENAME>Sales</VOUCHERTYPENAME>'.
						'<REFERENCE>'.$row['bill_no'].'</REFERENCE>'.
				 		'<VOUCHERNUMBER></VOUCHERNUMBER>'.
				 		'<PARTYLEDGERNAME>Cash</PARTYLEDGERNAME>'.
				 		'<BASICBASEPARTYNAME>Cash</BASICBASEPARTYNAME>'.
				 		'<CSTFORMISSUETYPE/>'.
				 		'<CSTFORMRECVTYPE/>'.
						'<FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>'.
				 		'<PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>'.
				 		'<VCHGSTCLASS/>'.
				 		'<DIFFACTUALQTY>No</DIFFACTUALQTY>'.
				 		'<ISMSTFROMSYNC>No</ISMSTFROMSYNC>'.
				 		'<ASORIGINAL>No</ASORIGINAL>'.
				 		'<AUDITED>No</AUDITED>'.
				 		'<FORJOBCOSTING>No</FORJOBCOSTING>'.
				 		'<ISOPTIONAL>No</ISOPTIONAL>'.
				 		'<EFFECTIVEDATE></EFFECTIVEDATE>'.
				 		'<USEFOREXCISE>No</USEFOREXCISE>'.
				 		'<ISFORJOBWORKIN>No</ISFORJOBWORKIN>'.
				 		'<ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>'.
				 		'<USEFORINTEREST>No</USEFORINTEREST>'.
				 		'<USEFORGAINLOSS>No</USEFORGAINLOSS>'.
				 		'<USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>'.
				 		'<USEFORCOMPOUND>No</USEFORCOMPOUND>'.
				 		'<USEFORSERVICETAX>No</USEFORSERVICETAX>'.
				 		'<ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>'.
				 		'<EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>'.
				 		'<USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>'.
				 		'<EXCISEOPENING>No</EXCISEOPENING>'.
				 		'<USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>'.
				 		'<ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>'.
				 		'<ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>'.
				 		'<ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>'.
				 		'<INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>'.
				 		'<ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>'.
				 		'<ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>'.
				 		'<IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>'.
				 		'<ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>'.
				 		'<ISISDVOUCHER>No</ISISDVOUCHER>'.
				 		'<ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>'.
				 		'<ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>'.
				 		'<ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>'.
				 		'<GSTNOTEXPORTED>No</GSTNOTEXPORTED>'.
				 		'<ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>'.
				 		'<ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>'.
				 		'<ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>'.
				 		'<ISCANCELLED>No</ISCANCELLED>'.
				 		'<HASCASHFLOW>No</HASCASHFLOW>'.
				 		'<ISPOSTDATED>No</ISPOSTDATED>'.
				 		'<USETRACKINGNUMBER>No</USETRACKINGNUMBER>'.
				 		'<ISINVOICE>Yes</ISINVOICE>'.
				 		'<MFGJOURNAL>No</MFGJOURNAL>'.
				 		'<HASDISCOUNTS>No</HASDISCOUNTS>'.
				 		'<ASPAYSLIP>No</ASPAYSLIP>'.
				 		'<ISCOSTCENTRE>No</ISCOSTCENTRE>'.
				 		'<ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>'.
				 		'<ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>'.
				 		'<ISBLANKCHEQUE>No</ISBLANKCHEQUE>'.
				 		'<ISVOID>No</ISVOID>'.
				 		'<ISONHOLD>No</ISONHOLD>'.
				 		'<ORDERLINESTATUS>No</ORDERLINESTATUS>'.
				 		'<VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>'.
				 		'<VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>'.
				 		'<ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>'.
				 		'<VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>'.
				 		'<ISVATDUTYPAID>Yes</ISVATDUTYPAID>'.
				 		'<ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>'.
				 		'<ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>'.
				 		'<ISDELETED>No</ISDELETED>'.
				 		'<CHANGEVCHMODE>No</CHANGEVCHMODE>'.
				 		'<VOUCHERKEY>'.$row['bill_no'].'</VOUCHERKEY>'.
				 		'<INVOICEORDERLIST.LIST>'.
							'<BASICORDERDATE>'.$visit_date.'</BASICORDERDATE>'.
							'<BASICPURCHASEORDERNO></BASICPURCHASEORDERNO>'.
				 		'</INVOICEORDERLIST.LIST>'.
				 		'<LEDGERENTRIES.LIST>'.
				  			'<OLDAUDITENTRYIDS.LIST TYPE="Number">'.
				   				'<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>'.
				  			'</OLDAUDITENTRYIDS.LIST>'.
							'<LEDGERNAME>Cash</LEDGERNAME>'.
							'<GSTCLASS/>'.
							'<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>'.
							'<LEDGERFROMITEM>No</LEDGERFROMITEM>'.
							'<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>'.
							'<ISPARTYLEDGER>Yes</ISPARTYLEDGER>'.
							'<ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>';
							if($row["tax_state"]==0)
                            {
                            	$amount=round($row["parcel_amt"]+$row["tax_amt"]+$row["bill_amt"]-$row["discount"]);
                            	$amount=0-$amount;
                                $voucher.='<AMOUNT>'.$amount.'</AMOUNT>';
                            }
                            else
                            {
                            	$amount=round($row["parcel_amt"]+$row["bill_amt"]-$row["discount"]);
                            	$amount=0-$amount;
                            	$voucher.='<AMOUNT>'.$amount.'</AMOUNT>';
                            }
							
							$voucher.='<BILLALLOCATIONS.LIST>'.
								'<NAME>'.$row['bill_no'].'</NAME>'.
								'<BILLTYPE>New Ref</BILLTYPE>'.
								'<TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>';

							if($row["tax_state"]==0)
                            {
                            	$amount=round($row["parcel_amt"]+$row["tax_amt"]+$row["bill_amt"]-$row["discount"]);
                            	$amount=0-$amount;
                                $voucher.='<AMOUNT>'.$amount.'</AMOUNT>';
                            }
                            else
                            {
                            	$amount=round($row["parcel_amt"]+$row["bill_amt"]-$row["discount"]);
                            	$amount=0-$amount;
                            	$voucher.='<AMOUNT>'.$amount.'</AMOUNT>';
                            }
							
							$voucher.='</BILLALLOCATIONS.LIST>'.
						'</LEDGERENTRIES.LIST>';


						$bill_no=$row['bill_no'];
                        $trans_query=$db->prepare("select transaction_id from transaction_mst where transaction_mst.device_id='$d_id' and bill_no='$bill_no'");
                        $trans_query->execute();
                        while($trans_data=$trans_query->fetch())
                        {
                            $transaction_id=$trans_data['transaction_id'];
                        }

                        $count_query=$db->prepare("select DISTINCT item_name, item_id, quantity, price, total_amt, abbrevation, transaction_dtl.status from transaction_dtl, unit_mst, stock_mst where transaction_dtl.transaction_id='$transaction_id' and stock_mst.product_id=item_id and unit_mst.unit_id=stock_mst.unit_id");

                        $count_query->execute();                        
                        while ($row_cnt=$count_query->fetch()) 
                        {
                        	if($row_cnt['status']=="cancel")
                            {
                            }
                            else
                            {
                            	if($row["tax_state"]==0)
                            	{
	                            	$product_id=$row_cnt['item_id'];
	                            	$tax_query=$db->prepare("select tax_mst.tax_id from tax_mst, price_mst where price_mst.tax_id=tax_mst.tax_id and price_mst.product_id='$product_id'");
	                            	$tax_query->execute();
	                            	while($tax_data=$tax_query->fetch())
	                            	{
	                            		switch($tax_data['tax_id'])
	                            		{

	                            			case 1:
	                            			$flag1=1;
	                            			$sgst0=0;
	                            			$cgst0=0;
	                            			break;
	                            			case 2:
	                            			$flag2=1;
	                            			$sgst5+=$row_cnt['total_amt']*0.025;
	                            			$cgst5+=$row_cnt['total_amt']*0.025;
	                            			break;
	                            			case 3:
	                            			$flag3=1;
	                            			$sgst12+=$row_cnt['total_amt']*0.06;
	                            			$cgst12+=$row_cnt['total_amt']*0.06;
	                            			break;
	                            			case 4:
	                            			$flag4=1;
	                            			$sgst18+=$row_cnt['total_amt']*0.09;
	                            			$cgst18+=$row_cnt['total_amt']*0.09;
	                            			break;
	                            			case 5:
	                            			$flag5=1;
	                            			$sgst28+=$row_cnt['total_amt']*0.14;
	                            			$cgst28+=$row_cnt['total_amt']*0.14;
	                            			break;
	                            		}
	                            	}
	                            }
	                            else
	                            {
	                            	$product_id=$row_cnt['item_id'];
	                            	$tax_query=$db->prepare("select tax_mst.tax_id from tax_mst, price_mst where price_mst.tax_id=tax_mst.tax_id and price_mst.product_id='$product_id'");

	                            	$tax_query->execute();
	                            	while($tax_data=$tax_query->fetch())
	                            	{
	                            		switch($tax_data['tax_id'])
	                            		{
	                            			case 1 :
	                            			$flag1=1;
	                            			$sgst0=0;
	                            			$cgst0=0;
	                            			break;
	                            			case 2 :
	                            			$flag2=1;
	                            			$sgst5+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.025));
	                            			$cgst5+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.025));
	                            			break;
	                            			case 3 :
	                            			$flag3=1;
	                            			$sgst12+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.06));
	                            			$cgst12+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.06));
	                            			break;
	                            			case 4 :
	                            			$flag4=1;
	                            			$sgst18+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.09));
	                            			$cgst18+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.09));
	                            			break;
	                            			case 5 :
	                            			$flag5=1;
	                            			$sgst28+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.14));
	                            			$cgst28+=($row_cnt['total_amt']-($row_cnt['total_amt']/1.14));
	                            			break;
	                            		}
	                            	}

	                            }
	                        }
	                    }

	                    if($flag1==1)
	                    {
	                    	$voucher.='<LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>0</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output SGST @ 0%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>0.00</AMOUNT><VATEXPAMOUNT>0.00</VATEXPAMOUNT></LEDGERENTRIES.LIST><LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>0%</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output CGST @ 0%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>0.00</AMOUNT><VATEXPAMOUNT>0.00</VATEXPAMOUNT></LEDGERENTRIES.LIST>';
	                    }
	                    elseif ($flag2==1) {
	                    	$voucher.='<LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>2.5</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output SGST @ 2.50%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$sgst5.'</AMOUNT><VATEXPAMOUNT>'.$sgst5.'</VATEXPAMOUNT></LEDGERENTRIES.LIST><LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>2.5</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output CGST @ 2.50%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$cgst5.'</AMOUNT><VATEXPAMOUNT>'.$cgst5.'</VATEXPAMOUNT></LEDGERENTRIES.LIST>';
	                    }
	                    elseif ($flag3==1) {
	                    	$voucher.='<LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>6</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output SGST @ 6%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$sgst12.'</AMOUNT><VATEXPAMOUNT>'.$sgst12.'</VATEXPAMOUNT></LEDGERENTRIES.LIST><LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>6</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output CGST @ 6%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$cgst12.'</AMOUNT><VATEXPAMOUNT>'.$cgst12.'</VATEXPAMOUNT></LEDGERENTRIES.LIST>';
	                    }

	                     elseif ($flag4==1) {
	                    	$voucher.='<LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>9</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output SGST @ 9%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$sgst18.'</AMOUNT><VATEXPAMOUNT>'.$sgst18.'</VATEXPAMOUNT></LEDGERENTRIES.LIST><LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>9</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output CGST @ 9%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$cgst18.'</AMOUNT><VATEXPAMOUNT>'.$cgst18.'</VATEXPAMOUNT></LEDGERENTRIES.LIST>';
	                    }

	                    elseif ($flag5==1) {
	                    	$voucher.='<LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>14</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output SGST @ 14%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$sgst28.'</AMOUNT><VATEXPAMOUNT>'.$sgst28.'</VATEXPAMOUNT></LEDGERENTRIES.LIST><LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><BASICRATEOFINVOICETAX.LIST TYPE="Number"><BASICRATEOFINVOICETAX>14</BASICRATEOFINVOICETAX></BASICRATEOFINVOICETAX.LIST><ROUNDTYPE/><LEDGERNAME>Output CGST @ 14%</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$cgst28.'</AMOUNT><VATEXPAMOUNT>'.$cgst28.'</VATEXPAMOUNT></LEDGERENTRIES.LIST>';
	                    }

	                    $voucher.='<LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><LEDGERNAME>Discount</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED><ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED><AMOUNT>-'.$row['discount'].'</AMOUNT><VATEXPAMOUNT>-'.$row['discount'].'</VATEXPAMOUNT></LEDGERENTRIES.LIST><LEDGERENTRIES.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><LEDGERNAME>Parcel</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED><ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED><AMOUNT>'.$row['parcel_amt'].'</AMOUNT><VATEXPAMOUNT>'.$row['parcel_amt'].'</VATEXPAMOUNT></LEDGERENTRIES.LIST>';

	                    $count_query->execute();
	                    while ($row_cnt = $count_query->fetch()) 
                        {
                            if($row_cnt['status']=="cancel")
                            {
                            }
                            else
                            {
                            	if($row["tax_state"]==0)
                            	{
	                            	$voucher.='<ALLINVENTORYENTRIES.LIST><STOCKITEMNAME>'.$row_cnt['item_name'].'</STOCKITEMNAME><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><ISAUTONEGATE>No</ISAUTONEGATE><ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE><ISTRACKCOMPONENT>No</ISTRACKCOMPONENT><ISTRACKPRODUCTION>No</ISTRACKPRODUCTION><ISPRIMARYITEM>No</ISPRIMARYITEM><ISSCRAP>No</ISSCRAP><RATE>'.$row_cnt['price'].'/'.$row_cnt['abbrevation'].'</RATE><AMOUNT>'.$row_cnt['total_amt'].'</AMOUNT><ACTUALQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</ACTUALQTY><BILLEDQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</BILLEDQTY><BATCHALLOCATIONS.LIST><GODOWNNAME>Main Location</GODOWNNAME><BATCHNAME>Primary Batch</BATCHNAME><INDENTNO/><ORDERNO/><TRACKINGNUMBER></TRACKINGNUMBER><DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED><AMOUNT>'.$row_cnt['total_amt'].'</AMOUNT><ACTUALQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</ACTUALQTY><BILLEDQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</BILLEDQTY></BATCHALLOCATIONS.LIST><ACCOUNTINGALLOCATIONS.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><LEDGERNAME>Cash Sale</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$row_cnt['total_amt'].'</AMOUNT></ACCOUNTINGALLOCATIONS.LIST></ALLINVENTORYENTRIES.LIST>';
	                            }
	                            else
	                            {
	                            	$product_id=$row_cnt['item_id'];
	                            	$tax_query=$db->prepare("select tax_mst.tax_id from tax_mst, price_mst where price_mst.tax_id=tax_mst.tax_id and price_mst.product_id='$product_id'");
	                            	$tax_query->execute();
	                            	while($tax_data=$tax_query->fetch())
	                            	{
	                            		switch($tax_data['tax_id'])
	                            		{
	                            			case 1:
	                            			$price=$row_cnt['price'];
	                            			$total=$row_cnt['total_amt'];
	                            			break;
	                            			case 2:
	                            			$price=$row_cnt['price']/1.05;
	                            			$total=$row_cnt['total_amt']/1.05;
	                            			break;
	                            			case 3:
	                            			$price=$row_cnt['price']/1.12;
	                            			$total=$row_cnt['total_amt']/1.12;
	                            			break;
	                            			case 4:
	                            			$price=$row_cnt['price']/1.18;
	                            			$total=$row_cnt['total_amt']/1.18;
	                            			break;
	                            			case 5:
	                            			$price=$row_cnt['price']/1.28;
	                            			$total=$row_cnt['total_amt']/1.28;
	                            			break;
	                            		}

	                            		$voucher.='<ALLINVENTORYENTRIES.LIST><STOCKITEMNAME>'.$row_cnt['item_name'].'</STOCKITEMNAME><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><ISAUTONEGATE>No</ISAUTONEGATE><ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE><ISTRACKCOMPONENT>No</ISTRACKCOMPONENT><ISTRACKPRODUCTION>No</ISTRACKPRODUCTION><ISPRIMARYITEM>No</ISPRIMARYITEM><ISSCRAP>No</ISSCRAP><RATE>'.$price.'/'.$row_cnt['abbrevation'].'</RATE><AMOUNT>'.$total.'</AMOUNT><ACTUALQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</ACTUALQTY><BILLEDQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</BILLEDQTY><BATCHALLOCATIONS.LIST><GODOWNNAME>Main Location</GODOWNNAME><BATCHNAME>Primary Batch</BATCHNAME><INDENTNO/><ORDERNO/><TRACKINGNUMBER></TRACKINGNUMBER><DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED><AMOUNT>'.$total.'</AMOUNT><ACTUALQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</ACTUALQTY><BILLEDQTY>'.$row_cnt['quantity'].' '.$row_cnt['abbrevation'].'</BILLEDQTY></BATCHALLOCATIONS.LIST><ACCOUNTINGALLOCATIONS.LIST><OLDAUDITENTRYIDS.LIST TYPE="Number"><OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS></OLDAUDITENTRYIDS.LIST><LEDGERNAME>Cash Sale</LEDGERNAME><GSTCLASS/><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><LEDGERFROMITEM>No</LEDGERFROMITEM><REMOVEZEROENTRIES>No</REMOVEZEROENTRIES><ISPARTYLEDGER>No</ISPARTYLEDGER><ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE><AMOUNT>'.$total.'</AMOUNT></ACCOUNTINGALLOCATIONS.LIST></ALLINVENTORYENTRIES.LIST>';
	                            	}

	                            }
	                        }
	                    }
				 		$voucher.='</VOUCHER></TALLYMESSAGE>';	
    	}
    	while($row = $Product_query->fetch());
    }

    $data_array = array('page'=>$next,'total'=>$product_count,'voucher'=>$voucher);
    $response=json_encode($data_array);
    echo $response; 

?>