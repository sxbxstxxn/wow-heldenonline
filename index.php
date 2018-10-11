<?php

require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'cache' => false,
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());

$template = $twig->load('base.twig');

$thumbnail_prefix = "http://render-eu.worldofwarcraft.com/character/";

//Chargrp has to be 2 or 3 characters, not more, not less
$chargrp[] = array('golgari','firun');
$chargrp[] = array('famerlor','rhazzazor','xebulon');



//echo '<pre>';
foreach ($chargrp as $key=>$chars) { 
  foreach ($chars as $key2=>$char) {
    $allchars[$key][$char] = getCharInfo($char);
    $r = $allchars[$key][$char]['race'];
    $c = $allchars[$key][$char]['class'];
    $l = $allchars[$key][$char]['lastModified']/1000;
    $allchars[$key][$char]['race'] = getRace($r);
    $allchars[$key][$char]['raceid'] = $r;
    $allchars[$key][$char]['class'] = getClass($c);
    $allchars[$key][$char]['classid'] = $c;
    $allchars[$key][$char]['lastModified'] = date('d.m.Y H:i', $l);
  }
}
//var_dump($allchars);
//echo '</pre>';
//exit;

//$test = getClass(1);
//echo $test;
echo $template->render(array('title' => 'WOW Helden Online', 'chargroup' => $allchars, 'thumburl' => $thumbnail_prefix));


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
  $url = "https://eu.api.blizzard.com/wow/character/malfurion/".$charname."?namespace=dynamic-eu&locale=de_DE&fields=reputation";
  $authorization = "Authorization: Bearer ".$token;

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));  
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $preresult = json_decode(curl_exec($curl));

  $result = (array)$preresult;
  curl_close($curl);

  return $result;
}
function getRace($id) {
    $token = getToken();
    $url = "https://eu.api.blizzard.com/wow/data/character/races?namespace=dynamic-eu&locale=de_DE&fields=reputation";
    $authorization = "Authorization: Bearer ".$token;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $races = json_decode(curl_exec($curl),true);
    curl_close($curl);

    foreach ($races['races'] as $key=>$race) {
        if ($race['id'] == $id) {
            $result = $race['name'];
        }
    }

    return $result;
}
function getClass($id) {
    $token = getToken();
    $url = "https://eu.api.blizzard.com/wow/data/character/classes?namespace=dynamic-eu&locale=de_DE&fields=reputation";
    $authorization = "Authorization: Bearer ".$token;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $classes = json_decode(curl_exec($curl),true);
    curl_close($curl);

    foreach ($classes['classes'] as $key=>$class) {
        if ($class['id'] == $id) {
            $result = $class['name'];
        }
    }

    return $result;
}