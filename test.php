<?php
function access_token () {
	$clientid = "8fe7546c82744a2b8cc0a7bd58bc2fce";
	$secret = "TEaKrWz2QXy36TCPEPXJTT3sKJ8gTIBe";
	$url = "https://eu.battle.net/oauth/token?grant_type=client_credentials&client_id=$clientid&client_secret=$secret";
	
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	

	$output = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	

	$result = json_decode($output, true);
	if(isset($result['access_token'])){
		return $result['access_token'];
	} else {
		die("Failed to get access token.");
	}
}

$test = access_token();
echo $test;
