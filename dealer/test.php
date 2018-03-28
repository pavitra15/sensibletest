<?php
    $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

    $plaintext = "Sachin Khodade";

    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$plaintext, MCRYPT_MODE_CBC, $iv);

    $ciphertext = $iv . $ciphertext;
    
    $ciphertext_base64 = base64_encode($ciphertext);

    echo  $ciphertext_base64 . "</br>";


    echo strlen($ciphertext_base64). "</br>";;

    $ciphertext_dec = base64_decode($ciphertext_base64);
    
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    
    echo  $plaintext_dec . "\n";
?>