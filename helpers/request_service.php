<?php

namespace Helpers;

/**
 * Request_Service Class offers mostly getters/setters for current request info.
 * 
 * By using the methods of this class one can retrieve well formated info
 * based on the data stored in php global variables $_SESSION and $_REQUEST.
 */
abstract class Request_Service {

  /**
   * @var array $response_code : map http response code to text
   */
  public static $response_code = array(
    // 1xx Information
    100 => 'Continue',
    101 => 'Switching Protocols',
    // 2xx Success
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    // 3xx Redirect
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Moved Temporarily',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    // 4xx Client Specific
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Time-out',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Large',
    415 => 'Unsupported Media Type',
    // 5xx Server Specific
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Time-out',
    505 => 'HTTP Version not supported',
  );
  
  /**
   * set_response_code Function for setting the http header response code
   * @param int $code
   * @return boolean
   */
  public static function set_response_code( $code ){
    if ($text = static::$response_code[ $code ]){
      $protocol = static::get_request_protocol();
      header($protocol . ' ' . $code . ' ' . $text);
      return true;
    }
    return false;
  }

  /**
   * get_request_uri Function for parsing questested redirect url
   * @return array
   */
  public static function get_request_uri(){
    /*  The most common RESTful web site resource pattern is to add a view to the URI:
     *       / resource_type [/ identifier] [?params]
     */
    $uri = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL']:'/';
    $request_url = array();
    $pattern_parts = explode('/',$uri);
    $request_url['resource_type'] = isset($pattern_parts[1]) ? $pattern_parts[1]:false;
    $request_url['identifier'] = isset($pattern_parts[2]) ? (is_numeric($pattern_parts[2]) ? $pattern_parts[2]:false):false;
    $request_url['view'] = isset($pattern_parts[3]) ? $pattern_parts[3]:false;
    return $request_url;
  }

  /**
   * get_requested_uri Function for getting the questested / redirect uri
   * @return array
   */
  public static function get_requested_uri(){
    $requested_uri= static::get_server_name().(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI']:'');
    return $requested_uri;
  } 
  
  /**
   * get_server_name Function for retrieving the requested server name.
   * @return string
   */
  public static function get_server_name(){
    $server_name = $_ENV['PROTOCOL'] .'://'. (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME']:'');
    return $server_name;
  }
  
  /**
   * get_request_params Function for gettting $_GET and $_POST data.
   * @return array
   */
  public static function get_request_params(){
    $params = array();
    foreach ($_REQUEST as $key => $value) {
      $params[ $key ] = $value;
    }
    return $params;
  }

  /**
   * get_request_type Function for knowing the type of request.
   * @return string
   */
  public static function get_request_type(){
    if (isset($_REQUEST['method'])){
      $type = $_REQUEST['method'];
    } else {
      $type = false;
    }
    return $type;
  }

  /**
   * get_request_protocol Function for knowing the http header protocol
   * @return string
   */
  public static function get_request_protocol(){
    $protocol= (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1'); 
    return $protocol;   
  }

  /**
   * get_request_data Function for grabbing all the request info that is relevant to session.
   * @param array $request
   * @return array
   */
  public static function get_request_data( $request ){
    $updates = array();

    return $updates;        
  }
}