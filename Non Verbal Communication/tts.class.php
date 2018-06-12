<?php

/**
* this is a PHP class which converts text to spoken language
* Version: 2.0
*
* Usage:
* $d = tts::say("text to convert", "language_code");
*
* Language Codes can be found at http://portalas.org/scripts/tts/docs-v2/
*
* http://portalas.org/scripts/tts/
*/

DEFINE('APIKEY','7lfK1bMaR28rQ9CpLqmdE3gGVnDOwI'); //enter your api key here. Acquire your key by visiting http://portalas.org/scripts/tts/register/
DEFINE('AUDIO_DIR','/Non Verbal Communication/audio/'); // default: audio/ where url is domain.com/audio/


//do not change anything below 

DEFINE('BASE_URL', ((isset($_SERVER["HTTPS"])) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR);
DEFINE('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);

$lang = filter_var(strtolower($_POST['lang']),FILTER_SANITIZE_STRING);
$read = filter_var(strtolower($_POST['read']),FILTER_SANITIZE_STRING);

if ($read || $lang) {
 $d = tts::say($read, $lang);

echo $d;
}


class tts {

  public static $lang;
  public static $text;

  const download = true;


  private static function sanitize($string) {
    $string = str_replace(PHP_EOL, '', $string);
    $string = str_replace("'", "", $string);
    $string = str_replace("&", "and", $string);
    $string = str_replace('"', "", $string);
    $string = filter_var($string, FILTER_SANITIZE_STRING);
    $string = urldecode($string);

    return $string;

  }

  private static function isCached($file)
  {
    return ((file_exists(ROOT_DIR.AUDIO_DIR.$file. '.mp3')) ? true : false);
  }

  private static function audioURL($audio_file)
  {
    return BASE_URL . AUDIO_DIR . $audio_file . '.mp3';
  }


  private static function audioPath($audio_file)
  {
    return ROOT_DIR . AUDIO_DIR . $audio_file . '.mp3';
  }

  private static function hash()
  {
    return md5(self::$text."_".self::$lang);
  }

  private static function fetchAudio()
  {
      $url = "http://tts.portalas.org/api/";

      $post = array(
        "apiKey"  => APIKEY,
        "text"    => self::$text,
        "lang"    => self::$lang,
        "callURL" => BASE_URL
      );

      $curl = curl_init();
      $useragent="portalas TTS;http://tts.portalas.org";
      curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
      curl_setopt($curl, CURLOPT_REFERER, BASE_URL);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $ret = curl_exec($curl);
      curl_close($curl);
      return $ret;
  }

  private static function downloadLocally($url,$filename)
  {
    $audio = @file_get_contents($url);

    $action = ($audio) ? file_put_contents(self::audioPath($filename), $audio) : "e";
    return (($action != "e") ? self::audioURL($filename) : false);
  }

  public static function say($text,$lang)
  {

    self::$text = self::sanitize($text);
    self::$lang = self::sanitize($lang);

    $audio_file = self::hash();

    $isCached = self::isCached($audio_file);

    if ($isCached === true)
    {
      return self::audioURL($audio_file); die();
    }

    $fetched = json_decode(self::fetchAudio());

    if ($fetched->success == 0) {
      print_r($fetched);die();
    } else {
      $file_url = $fetched->url;
    }

    if (self::download)
    {
      $file_url = self::downloadLocally($file_url,$audio_file);
    }
    return $file_url;

  }


}


?>