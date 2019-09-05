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
    $char['items'] = json_decode($char['items']);
    $char['professions'] = json_decode($char['professions'],JSON_OBJECT_AS_ARRAY);
    $char['feed'] = json_decode($char['feed'], true);
    $char['talents'] = json_decode($char['talents'], true);
    if (!in_array($char['grp'],$grps)) {
        $grps[] = $char['grp'];
    }
}


foreach ($grps as $grp) {
    $allgrps[$grp] = array();
    foreach ($chars as $char) {
        $char['reputation'] = json_decode($char['reputation'],JSON_UNESCAPED_UNICODE);
        $char['items'] = json_decode($char['items'],JSON_UNESCAPED_UNICODE);
        $char['professions'] = json_decode($char['professions'],JSON_UNESCAPED_UNICODE);
        $char['feed'] = json_decode($char['feed'],JSON_UNESCAPED_UNICODE);
        $char['talents'] = json_decode($char['talents'],JSON_UNESCAPED_UNICODE); 
        if ($grp==$char['grp']) {
            $allgrps[$grp][] = $char;
        }
    }
}


//var_dump($test);exit;
/*
echo '<pre>';
$test = json_decode($allgrps[0][1]['feed'],true);
var_dump(json_last_error_msg());
echo '</pre>';
*/
# 7.Legion = 2159
# Sturmwwacht = 2162
# Armee des Lichts = 2165
# Argusvorstoß = 2170
# Champions von Azeroth = 2164
# Glutorden = 2161
# Prachtmeeradmiralität = 2160
$reputationgrp = array(2159);

// 2533 | Schneiderei von Kul Tiras
// 2486 | Verzauberkunst von Kul Tiras
// 2549 | Kräuterkunde von Kul Tiras
// 2507 | Inschriftenkunde von Kul Tiras
$professiongrp = array(2533,2486,2549,2507);

echo $template->render(array('title' => 'WOW Helden Online', 'chargroups' => $allgrps, 'thumburl' => $thumbnail_prefix, 'repgrp' => $reputationgrp, 'profgrp' => $professiongrp));

