
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


function encrypt($value, $key)
{
  $text = $value;
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);

  return $crypttext;
}


function decrypt($value, $key)
{
  $crypttext = $value;
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
  return trim($decrypttext);
}

$Data="12345";

$EncryptData = base64_encode(encrypt($Data, '123acd1245120954'));

echo $EncryptData;

$EncryptData=base64_decode($EncryptData);

// Use decrypt function to decrypt the encrypted data 
// You have to provide the encrypted data which you want to decrypt and provide secure key
// You have to use same secure key to get the plain text data from ciphertext
$DecryptData = decrypt($EncryptData, '123acd1245120954');
 
 echo $DecryptData;
 // echo $DecryptData ;	
?>


