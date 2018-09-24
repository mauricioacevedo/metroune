<?php
	$ch = curl_init();
// 	$localfile = $_FILES['upload']['tmp_name'];
 	$localfile = '/tmp/archivo.txt';
 	$fp = fopen($localfile, 'r');
 	curl_setopt($ch, CURLOPT_URL, 'ftp://root:develop1@10.65.164.18/'."tmp/archivo.txt");
 	curl_setopt($ch, CURLOPT_UPLOAD, 1);
 	curl_setopt($ch, CURLOPT_INFILE, $fp);
 	curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
 	curl_exec ($ch);
 	$error_no = curl_errno($ch);
 	curl_close ($ch);
        if ($error_no == 0) {
        	$error = 'File uploaded succesfully.';
        } else {
        	$error = 'File upload error.';
        }

	echo $error;
?>
