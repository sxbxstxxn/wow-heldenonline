<?php

//Chargrp has to be 2 or 3 characters, not more, not less
$chargrp[] = array('boron','firun');
$chargrp[] = array('rhazzazor');
$chargrp[] = array('golgari','ucuri');

//var_dump($chargrp);
/*
foreach ($chargrp as $key=>$chars) {
  foreach ($chars as $char) {
    save_char_in_db($char,$key);
  }
}
*/

$char = getCharInfo('boron');

$charfeed = json_encode($char['feed'],JSON_HEX_APOS ,JSON_HEX_QUOT);
$charfeed2 = preg_replace("/[\n\r]/","",$charfeed);
//$charfeed = str_replace("'","\'",$charfeed);
//$charfeed = str_replace('"','\"',$charfeed);
//$charfeed = str_replace("\r\n", " ",$charfeed);

$test = json_decode($charfeed,JSON_PARTIAL_OUTPUT_ON_ERROR );
//$test = $char['feed'];
$error = json_last_error();

/*
foreach ($test as $testkey => $testvalue) {
  //if (is_string($a)) {$a = preg_replace("/[\n\r]/","",$a);}  
  foreach ($testvalue as $akey => $avalue) {    
    //if (is_string($b)) {$b = preg_replace("/[\n\r]/","",$b);}
    foreach ($avalue as $bkey => $bvalue) {
      //if (is_string($c)) {$c = preg_replace("/[\n\r]/","",$c);}
      foreach ($bvalue as $ckey => $cvalue) {
        //if (is_string($d)) {$d = preg_replace("/[\n\r]/","",$d);}        
        foreach ($cvalue as $dkey => $dvalue) {
          //if (is_string($e)) {$e = preg_replace("/[\n\r]/","",$e);}
          $X = $test[$testkey][$akey][$bkey][$ckey][$dkey];
          if (is_string($X) && !isnull($X)) {$X = preg_replace("/[\n\r]/","",$X);}
          $test[$testkey][$akey][$bkey][$ckey][$dkey] = $X;          
        }
      }
    }
  }
}

$var = $test[18]['achievement']['criteria'][0]['description'];
*/


echo '<pre>';
//$chartalents = array($char['talents']);

/*
foreach ($chartalents as $key=>$chartalent) {
  $a = (array)$chartalent[$key];
  $b = (array)$a;
  if ($b['selected'] == true) {
    $c = array($b);
    echo 'Spec:';
    var_dump($c['spec']);
    echo '<br/>';
  }
  
}
*/


//$bla = str_replace("'","\'",json_encode($test['feed'],JSON_UNESCAPED_UNICODE));
//$bla = json_decode($bla,JSON_OBJECT_AS_ARRAY);
//$newstring = preg_replace("/[\n\r]/","",$var);
//var_dump($char['feed']);
//echo '<br/>';
$chartalents = json_encode($char['reputation'],JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
$chartalents = preg_replace("/[\n\r]/","",$chartalents);
//$chartalents = preg_replace("/[\n\r]/","",$chartalents);
/*
$chartalents = json_decode($chartalents,true);

foreach ($chartalents as $key=>$chartalent) {
  if ($chartalent['selected'] == true) {
    //echo 'Specname: '.$chartalent['spec']['name'].' | Specrole: '.$chartalent['spec']['role'].' | <img src="http://media.blizzard.com/wow/icons/56/'.$chartalent['spec']['icon'].'.jpg" title="'.$chartalent['spec']['description'].'"/>';
    var_dump($chartalent['spec']);
    echo '<br/>';
  }
}
*/


 $test2 = json_decode($chartalents,true,JSON_PARTIAL_OUTPUT_ON_ERROR );
    $error = json_last_error();
    if ($error !== 0) {
      $chartalents = json_encode('');
    }

foreach ($test2 as $test2key => $test2value) {
      //if (is_string($a)) {$a = preg_replace("/[\n\r]/","",$a);}  
      foreach ($test2value as $a2key => $a2value) {    
        //if (is_string($b)) {$b = preg_replace("/[\n\r]/","",$b);}
        foreach ($a2value as $b2key => $b2value) {
          //if (is_string($c)) {$c = preg_replace("/[\n\r]/","",$c);}
          foreach ($b2value as $c2key => $c2value) {
            //if (is_string($d)) {$d = preg_replace("/[\n\r]/","",$d);}        
            foreach ($c2value as $d2key => $d2value) {
              //if (is_string($e)) {$e = preg_replace("/[\n\r]/","",$e);}
              $X2 = $test2[$test2key][$a2key][$b2key][$c2key][$d2key];
              if (is_string($X2)) {$X2 = preg_replace("/[\n\r]/","",$X2);}
              $test2[$test2key][$a2key][$b2key][$c2key][$d2key] = $X2;          
            }
          }
        }
      }
    }    
$chartalentsnew = json_encode($test2,JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

$dfg = json_decode($chartalentsnew);
var_dump($test2);
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
  $url = "https://eu.api.blizzard.com/wow/character/malfurion/".$charname."?namespace=dynamic-eu&locale=de_DE&fields=reputation,feed,items,professions,progression,stats,statistics,talents";
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
