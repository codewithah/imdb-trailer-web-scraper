<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//require_once('simpleHtmlDom.php');

function geturl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$ip = rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
}


function getIMDbTrailer($IDMovie){

$imdbID = trim($IDMovie);

$url = geturl("https://www.imdb.com/title/{$imdbID}/");

echo $url;

if(preg_match('/vi\\d+/i',$url,$match))
{
	$trailerID = $match[0];
	$url_2 = geturl("https://www.imdb.com/video/imdb/{$trailerID}/imdb/single?vPage=1");
	preg_match_all('/<script class=\"imdb-player-data\" type=\"text\/imdb-video-player-json\">(.*?)<\/script>/ms', $url_2, $matches);
 $content = json_decode(trim($matches[1][0]));
  foreach($content as $element => $value){ // main foreach
    if(preg_match('/videoPlayerObject/i', $element)){ //if one
      foreach($value as $element => $value){ // foreach one
        foreach ($value as $element => $value) { // foreach tow
          if(preg_match('/videoInfoList/', $element)){ // if two
            foreach ($value as $element => $value) { // foreach three
              foreach ($value as $element => $value) { // foreach four
                $values[] = $value;
              }
            } // end foreach three
          } // end if two
        } //end foreach two
      } // end foreach one
    } // end if one
  } // end main foreach
  $c = 0;
  while(TRUE){
    if(preg_match('/video\/(.*?)/', $values[$c])){
      $trailer = $values[$c+1];
      break;
    }
    $c++;
  }
  return trim($trailer);
} else return '';
}
$id=$_GET['id'];
 echo getIMDbTrailer($id);

?>
