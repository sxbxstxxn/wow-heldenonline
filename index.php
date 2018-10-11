<?php

require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'cache' => false,
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());

$template = $twig->load('base.twig');

$chargrp[] = array('golgari','firun');
$chargrp[] = array('famerlor','teklador','golgari');



//echo '<pre>';
foreach ($chargrp as $key=>$chars) { 
  foreach ($chars as $key2=>$char) {
    $allchars[$key][$char] = getCharInfo($char);
  }
}
//var_dump($allchars);
//echo '</pre>';
//exit;

//echo $template->render(array('title' => 'WoW Helden Online', 'golgari' => $golgari));
echo $template->render(array('chargroup' => $allchars));


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
function getCharInfo($charname) {
  
  $token = getToken();
  $url = "https://eu.api.blizzard.com/wow/character/malfurion/".$charname."?namespace=dynamic-eu&locale=de_DE";
  $authorization = "Authorization: Bearer ".$token;

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));  
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $result = json_decode(curl_exec($curl));
  curl_close($curl);

  return $result;
}