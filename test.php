<?php

//Chargrp has to be 2 or 3 characters, not more, not less
$chargrp[] = array('golgari','firun');
$chargrp[] = array('rhazzazor');
$chargrp[] = array('famerlor');

//var_dump($chargrp);
/*
foreach ($chargrp as $key=>$chars) {
  foreach ($chars as $char) {
    save_char_in_db($char,$key);
  }
}
*/

$test = getCharInfo('golgari');
echo '<pre>';
$bla = str_replace("'","\'",json_encode($test['professions'],JSON_UNESCAPED_UNICODE));
$bla = json_decode($bla,JSON_OBJECT_AS_ARRAY);
var_dump($bla["primary"]);
echo '</pre>';



function save_char_in_db($charname,$chargrp) {
  
  $db_host = 'localhost';
  $db_username = 'whdon';
  $db_password = 'Pl95wy$2';
  $db_name = 'DB_whdon';  
  $db = mysqli_connect($db_host,$db_username,$db_password,$db_name);
  
  if ($db) {    
    $char = getCharInfo($charname);

    $charreputation = str_replace("'","\'",json_encode($char['reputation'],JSON_UNESCAPED_UNICODE));
    $charitems = str_replace("'","\'",json_encode($char['items'],JSON_UNESCAPED_UNICODE));
    $charprofessions = str_replace("'","\'",json_encode($char['professions'],JSON_UNESCAPED_UNICODE));
    $charfeed = str_replace("'","\'",json_encode($char['feed'],JSON_UNESCAPED_UNICODE));
    $charfeed = str_replace('"','\"',$charfeed);
    //$charfeed = str_replace("'","\'",json_encode($char['feed']));
                                  
    $sql = "INSERT INTO chars (name, grp, level, thumbnail, race, class, lastModified, feed, reputation, items, professions) VALUES 
            ('".$char['name']."',".$chargrp.", ".$char['level'].", '".$char['thumbnail']."', '".$char['race']."', '".$char['class']."', ".$char['lastModified'].", '".$charfeed."', '".$charreputation."', '".$charitems."', '".$charprofessions."')            
            ON DUPLICATE KEY UPDATE
            grp = values(grp),
            level = values(level),
            thumbnail = values(thumbnail),
            race = values(race),
            class = values(class),
            lastModified = values(lastModified),
            reputation = values(reputation),
            feed = values(feed),
            items = values(items),
            professions = values(professions)
            ";
    //var_dump($sql);exit;
    if (mysqli_query($db, $sql)) {
        echo "New record (Char: ".$char['name'].") created successfully<br/>";
    } else {
       echo "Error: " . $sql . "<br>" . mysqli_error($db);
      //echo "Error: <br>" . mysqli_error($db);
    }   
  }
  else {
    die("Connection failed: " . mysqli_connect_error());
  }       
  //mysql_close($db);  
}
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
  $url = "https://eu.api.blizzard.com/wow/character/malfurion/".$charname."?namespace=dynamic-eu&locale=de_DE&fields=reputation,feed,items,professions,progression,stats";
  $authorization = "Authorization: Bearer ".$token;

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));  
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $preresult = json_decode(curl_exec($curl));

  $result = (array)$preresult;
  curl_close($curl);

  $result['class'] = getClass($result['class']);
  $result['race'] = getRace($result['race']);
  $result['lastModified'] = $result['lastModified']/1000;

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


function clear_db() {
  
  $db_host = 'localhost';
  $db_username = 'whdon';
  $db_password = 'Pl95wy$2';
  $db_name = 'DB_whdon';  
  $db = mysqli_connect($db_host,$db_username,$db_password,$db_name);
  
  if ($db) {    
    
                                  
    $sql = "DELETE FROM chars";
    //var_dump($sql);exit;
    if (mysqli_query($db, $sql)) {
        echo "Database cleared<br/>";
    } else {
       echo "Error: " . $sql . "<br>" . mysqli_error($db);
      //echo "Error: <br>" . mysqli_error($db);
    }   
  }
  else {
    die("Connection failed: " . mysqli_connect_error());
  }  
}
?>
