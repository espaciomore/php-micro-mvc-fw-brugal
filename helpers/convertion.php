<?php
 
namespace Helpers;

/**
 * Convertion Class provides convertion from one object to another 
 * as well as encoding and decoding methods.
 *
 * This object methods are meant to be public and access from any 
 * class as many times as needed.
 */
abstract class Convertion {

  /**
   * get_instance Function for instantiating object from class names.
   * @param string $class_name
   * @return object
   */
  public static function get_instance($class_name){
    return new $class_name;
  }

  /**
   * toJSon Function for translating arrays to JSon format.
   * @param array $obj
   * @return string
   */
  public static function toJSon($obj) {
    return json_encode($obj);
  }

  /**
   * toStdClass Function for translating JSon format to object(stdClass)
   * @param string $obj
   * @return object(stdClass)
   */
  public static function toStdClass($obj) {
    return json_decode($obj); 
  }

  /**
   * ArrayToStdClass Function for translating arrays to object(stdClass)
   * @param string $obj
   * @return object(stdClass)
   */
  public static function arrayToStdClass($obj) {
    return json_decode(json_encode($obj)); 
  }
  
  /**
   * toClassName Function for reformatting string as class name.
   * @param string $str
   * @return string
   */
  public static function toClassName($str){
    $parts = array();
    foreach(explode('_', $str) as $index => $name){
      $parts[$index] = ucfirst($name);
    }
    return implode('_', $parts);
  } 

  /**
   * param_encode Function for getting the encoded http equivalent.
   * @param string $param
   * @return string
   */
  public static function param_encode( $param ){
    return urlencode( $param );
  }

  /**
   * param_decode Function for getting the real characters from http equivalent.
   * @param string $param
   * @return string
   */
  public static function param_decode( $param ){
    return urldecode( $param );
  }

  /**
   * hash_to_string_array Function for translating hash array to numeric indexed array
   * @param array $hash
   * @param string $glue
   * @return array
   */
  public static function hash_to_string_array( $hash, $glue='=' ){
    $arr = array();
    foreach ($hash as $key => $value) {
      array_push($arr, $key.$glue.$value);
    }
    return $arr;
  }

  /**
   * time_encode Function for encoding or encrypting time.
   * @param string $time
   * @return string
   */
  public static function time_encode( $time ){
    $str = $time{0};
    $len = strlen($time);
    for ($i = 1; $i < $len; $i++ ) {
      $str = $str . $time{$i};
      $str = $str . (rand()&9);
    }
    return $str;
  }

  /**
   * time_encode Function for decoding or decrypting time.
   * @param string $time
   * @return string
   */
  public static function time_decode( $time ){
    $str = $time{0};
    $len = strlen($time);
    for ($i = 1; $i < $len; $i++ ) {
      if ($i%2){
        $str = $str . $time{$i};
      }
    }
    return $str;
  }   

  /**
   * encode_user_data Function for encoding stateless session data.
   * @param array $params
   * @return string
   */
  public static function encode_user_data( $params ){
    $str_data = static::toJSon( $params );
    return base64_encode( $str_data . hash('md5','stateless_session') );
  }

  /**
   * decode_user_data Function for decoding stateless session data.
   * @param string $params
   * @return string
   */
  public static function decode_user_data( $data ){
    $params_plus_key = base64_decode($data);
    return str_replace( hash('md5','stateless_session'), '', $params_plus_key);
  } 

}