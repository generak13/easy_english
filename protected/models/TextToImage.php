<?php

// FileName: tts.php
/*
 *  A PHP Class that converts Text into Speech using Google's Text to Speech API
 *
 * Author:
 * Abu Ashraf Masnun
 * http://masnun.com
 *
 */

class TextToImage {

  public static function getImageUrlByText($text) {
		$json = self::get_url_contents('http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=' . urlencode($text) . '&safe=active');
		$data = json_decode($json, true);
		
    foreach ($data['responseData']['results'] as $elem) {
      if(strpos($elem['url'], 'demotivators') === false) {
        return $data['responseData']['results'][0]['url'];
      }
    }
		
		return '';
	}
	
	private static function get_url_contents($url) {
    $crl = curl_init();

    curl_setopt($crl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);

    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
	}	
}
?>