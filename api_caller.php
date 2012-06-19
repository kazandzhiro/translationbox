<?php 
// $tr_nonce = $_REQUEST['trbox_wpnonce'];
// if (! wp_verify_nonce($tr_nonce, 'tr-box') ) die("Security check");
	$from = $_GET['from'];
	$to = $_GET['to'];
	$text = $_GET['text'];
	$ch = curl_init();
	$text = urlencode($text);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch,CURLOPT_HTTPHEADER,array (
	        "Content-Type: text/xml; charset=utf-8",
	    ));
	curl_setopt($ch, CURLOPT_URL, "http://mymemory.translated.net/api/get?q={$text}&langpair={$from}|{$to}");
	$response = curl_exec($ch);
	echo $response;