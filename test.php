
<?php
	// include('connect.php');
	// require __DIR__ . '/vendor/autoload.php';
	// use Google\Cloud\Translate\TranslateClient;
	// $projectId = 'nth-weft-193604';
	// $translate = new TranslateClient([
	//     'projectId' => $projectId
	// ]);


	// $page_no=$_POST['page_no'];
	// $next=$page_no+1;
	// echo $next;
	// $limit=30;
	// $start_from = ($page_no-1) * $limit;
	// $product_query=$db->prepare("select product_id, English from product_mst  LIMIT $start_from , $limit");
	// $product_query->execute();
	// if($product_data=$product_query->fetch())
	// {
	// 	do
	// 	{
	// 		$product_id=$product_data['product_id'];
	// 		$text=$product_data['English'];

	// 		$targethi = 'hi';
	// 		$translationhi = $translate->translate($text, [
	// 		    'target' => $targethi
	// 		]);
	// 		$hindi=$translationhi['text'];

	// 		$targetmr = 'mr';
	// 		$translationmr = $translate->translate($text, [
	// 		    'target' => $targetmr
	// 		]);
	// 		$marathi=$translationmr['text'];

	// 		$targetpa = 'pa';
	// 		$translationpa = $translate->translate($text, [
	// 		    'target' => $targetpa
	// 		]);
	// 		$punjabi=$translationpa['text'];

	// 		$targetkn = 'kn';
	// 		$translationkn = $translate->translate($text, [
	// 		    'target' => $targetkn
	// 		]);
	// 		$kannada=$translationkn['text'];

	// 		$targetta = 'ta';
	// 		$translationta = $translate->translate($text, [
	// 		    'target' => $targetta
	// 		]);
	// 		$tamil=$translationta['text'];

	// 		$targette = 'te';
	// 		$translationte = $translate->translate($text, [
	// 		    'target' => $targette
	// 		]);
	// 		$telugu=$translationte['text'];

	// 		$targetbn = 'bn';
	// 		$translationbn = $translate->translate($text, [
	// 		    'target' => $targetbn
	// 		]);
	// 		$bengali=$translationbn['text'];

	// 		$targetgu = 'gu';
	// 		$translationgu = $translate->translate($text, [
	// 		    'target' => $targetgu
	// 		]);
	// 		$gujarati=$translationgu['text'];

	// 		try {
	// 			$db->beginTransaction();
	// 			$query=$db->prepare("update product_mst set Hindi='$hindi', Marathi='$marathi', Punjabi='$punjabi', Kannada='$kannada', Tamil='$tamil', Telugu='$telugu', Bengali='$bengali', Gujarati='$gujarati' where product_id='$product_id'");
	// 			$query->execute();
	// 			$db->commit();
	// 		} catch (Exception $e) {
	// 			$db->rollBack();
	// 			// echo $e;

	// 		}
			
	// 		}
	// 		while($product_data=$product_query->fetch());
	// 	}

		
 echo 
 	
?>


