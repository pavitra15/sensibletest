<body>
	<div>ok</div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() 
    {  
       	$.ajax({
		type: "POST",
			url: "calc.php",
			cache: false,
			success: function(data)
			{
				console.log(data);
				alert(data);
			    var total_page=parseInt(data)/30;
			    recursiveAjaxCall(total_page,1);
			}
		});
    });

	function recursiveAjaxCall(aNum,currentNum)
	{
	  	$.ajax({
	    	type: 'POST',
	    	url: 'test.php',
	    	data: {page_no: currentNum},
	    	success: function(ks)
	    	{
	    		alert(ks);
	    		console.log(ks);
	        	if(ks<aNum)
	        	{
	            	recursiveAjaxCall(aNum,ks);
	        	}
	        	else
	        	{
	            
	        	}
	    	},
	    	async:   true
	  	});
	}
</script>

UPDATE transaction_dtl INNER JOIN product ON transaction_dtl.item_id = product.product_id SET transaction_dtl.item_name = product.english_name WHERE transaction_dtl.item_id = product.product_id and transaction_dtl.item_name is null