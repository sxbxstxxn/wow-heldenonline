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

$db_host = 'localhost';
$db_username = 'whdon';
$db_password = 'Pl95wy$2';
$db_name = 'DB_whdon';  
$db = mysqli_connect($db_host,$db_username,$db_password,$db_name);

if ($db) { 
  $sql = "SELECT * FROM chars";
  if (mysqli_query($db, $sql)) {
    $result = mysqli_query($db, $sql);
    $chars = mysqli_fetch_all($result,MYSQLI_ASSOC);
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
  }   
}
else {
  die("Connection failed: " . mysqli_connect_error());
}

$grps = array();
//echo '<pre>';
foreach ($chars as $key=>$char) {
    $char['reputation'] = json_decode($char['reputation']);
    if (!in_array($char['grp'],$grps)) {
        $grps[] = $char['grp'];
    }
}


foreach ($grps as $grp) {
    $allgrps[$grp] = array();
    foreach ($chars as $char) {
        $char['reputation'] = json_decode($char['reputation'],JSON_UNESCAPED_UNICODE);
        if ($grp==$char['grp']) {
            $allgrps[$grp][] = $char;
        }
    }
}
/*
echo '<pre>';
var_dump($allgrps);
echo '<pre>';
*/

$reputationgrp = array(2165,2170,2159,2162);

echo $template->render(array('title' => 'WOW Helden Online', 'chargroups' => $allgrps, 'thumburl' => $thumbnail_prefix, 'repgrp' => $reputationgrp));

