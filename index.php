<?php

function getToken() {

  //curl -u {client_id}:{client_secret} -d grant_type=client_credentials https://us.battle.net/oauth/token

  $client_id = '8fe7546c82744a2b8cc0a7bd58bc2fce';
  $client_secret = 'TEaKrWz2QXy36TCPEPXJTT3sKJ8gTIBe';
  $url = "https://eu.battle.net/oauth/token";
  $params = ['grant_type'=>'client_credentials'];

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
  curl_setopt($curl, CURLOPT_USERPWD, $client_id.':'.$client_secret);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  
  $result = json_decode(curl_exec($curl));
  

  curl_close($curl);
  return $result->access_token;
}

function getRaces($token) {
  //curl -H "Authorization: Bearer {access_token}" https://us.api.blizzard.com/data/wow/token/?namespace=dynamic-us

  //$url = "https://eu.api.blizzard.com/data/wow/token/";
  //$url = "https://eu.api.battle.net/wow/character/races";
  //$url = "https://eu.api.battle.net/data/wow/connected-realm/";
  //$url = "https://eu.api.battle.net/wow/realm/status";
  $url = "https://eu.api.blizzard.com/wow/data/character/races?namespace=dynamic-eu";
  $authorization = "Authorization: Bearer ".$token;

//$result = $authorization;
//var_dump($authorization);exit;

//  $params = ['namespace'=>'dynamic-eu'];

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));  
  //curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_URL, $url);
  //curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $result = curl_exec($curl);

  curl_close($curl);
//var_dump($result);exit;

  return $result;
  
}


$token = getToken();
$test = getRaces($token);

var_dump($test);

?>
