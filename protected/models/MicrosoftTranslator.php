<?php

class MicrosoftTranslator
{
  private static $clientID = 'easy_english';
  private static $clientSecret = "PisEHgx6hfhuix5sMFvccVhB1WsZA/fzRG/x4nVoia4=";
  private static $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
  private static $scopeUrl = "http://api.microsofttranslator.com";
  private static $grantType = "client_credentials";
  
  private static $fromLanguage = 'en';
  private static $toLanguage = 'uk';
  private static $contentType = 'text/plain';
  private static $catagory = 'general';

  public static function translate($text) {
    if(!$text) {
      return false;
    }
    
    try {
      $access_token  = self::get_tockens();

      //Create the authorization Header string.
      $auth_header = "Authorization: Bearer ". $access_token;

      $params = "text=" . urlencode($text) . "&to=" . self::$toLanguage."&from=" . self::$fromLanguage;
      $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";

      $curl_response = self::get_curl_respose($translateUrl, $auth_header);

      //Interprets a string of XML into an object.
      $xmlObj = simplexml_load_string($curl_response, 'SimpleXMLElement', LIBXML_NOCDATA);
      $translations = json_decode(json_encode((array)$xmlObj), TRUE);
      
      return $translations;
    }  catch (Exception $e) {
      return false;
    }
  }
  
  private static function get_tockens() {
    try {
      //Initialize the Curl Session.
      $ch = curl_init();
      //Create the request Array.
      $paramArr = array (
           'grant_type'    => self::$grantType,
           'scope'         => self::$scopeUrl,
           'client_id'     => self::$clientID,
           'client_secret' => self::$clientSecret
      );
      //Create an Http Query.//
      $paramArr = http_build_query($paramArr);
      //Set the Curl URL.
      curl_setopt($ch, CURLOPT_URL, self::$authUrl);
      //Set HTTP POST Request.
      curl_setopt($ch, CURLOPT_POST, TRUE);
      //Set data to POST in HTTP "POST" Operation.
      curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
      //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
      //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      //Execute the  cURL session.
      $strResponse = curl_exec($ch);
      //Get the Error Code returned by Curl.
      $curlErrno = curl_errno($ch);
      if($curlErrno){
          $curlError = curl_error($ch);
          throw new Exception($curlError);
      }
      //Close the Curl Session.
      curl_close($ch);
      //Decode the returned JSON string.
      $result = json_decode($strResponse, true);
      if (isset($result['error'])){
          throw new Exception($result['error_description']);
      }
        return $result['access_token'];
    } catch (Exception $e) {
        echo "Exception-".$e->getMessage();
    }
  }
  
  private static function get_curl_respose($url, $authHeader) {
    //Initialize the Curl Session.
     $ch = curl_init();
     //Set the Curl url.
     curl_setopt ($ch, CURLOPT_URL, $url);
     //Set the HTTP HEADER Fields.
     curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
     //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
     curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
     //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
     curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
     //Execute the  cURL session.
     $curlResponse = curl_exec($ch);

     //Get the Error Code returned by Curl.
     $curlErrno = curl_errno($ch);
     if ($curlErrno) {
         $curlError = curl_error($ch);
         throw new Exception($curlError);
     }
     //Close a cURL session.
     curl_close($ch);
     return $curlResponse;
  }
}
