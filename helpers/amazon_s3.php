<?php

namespace Helpers;

/**
 * Amazon_S3 Class provides an interface to connect to //s3.amazonaws.com/
 *
 * This object methods are meant to upload resources to an amazon s3 bucket.
 */
abstract class Amazon_S3{

  /**
   * @var array $config : maps to connection parameters.
   */
  protected static $config= array(
    'bucket'=> 'bucket-name',
    'filename'=> '',
    'aws_access_key'=> 'AAAAAAAAAAAAAAAAAAAA',
    'aws_secret_key'=> 'E3dOUpv001ofEuVQnXsa1FesxjTlkLTxtx3cRF/X',
    'headers'=> array(
        'authorization'=> '',
        'content-md5'=> '',
        'content-type'=> '',
        'content-length'=> 0,
        'x-amz-acl'=> 'public-read',
        'x-amz-date'=> '',  //  D, d M Y G:i:s T
        'x-amz-meta-author'=> 'AUTHOR',
      ),
    'host'=> 'http://s3.amazonaws.com/',
    );

  /**
   * upload Function for uploading the resource.
   * @param string $source_file
   * @param string $content_type
   * @param string $filename
   * @param string $date
   * @return mixxed
   */
  public static function upload( $source_file,$content_type,$filename,$date=null ){
    if(!$date){
      $date= date("D, d M Y G:i:s T");
    }
    $filename= trim($filename,'/');        
    $get_content= function( $path,$timeout=2 ){
      set_time_limit($timeout);
      return file_get_contents($path,'r');
    };
    $content= $get_content( $source_file );
    if( $content===false and $content===null ){
      return false;
    }
    static::set_filename( $filename );
    static::set_content_type( $content_type );
    static::set_content_length( $content );
    static::set_content_md5( $content );
    static::set_date( $date );
    static::set_authorization( static::get_string_to_sign() );
    // use CURL to send request 
    $upload= function( $url,$headers,$body ){
      $ch= curl_init();
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER,1 );
      curl_setopt( $ch,CURLOPT_CUSTOMREQUEST,'PUT' );
      curl_setopt( $ch,CURLOPT_URL,rawurldecode($url) );
      curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers );
      curl_setopt( $ch,CURLOPT_POSTFIELDS,$body );
      $result= curl_exec( $ch );
      curl_close( $ch );   

      return $result;
    };
    $response= $upload(
      static::$config['host'].static::$config['bucket'].$_ENV['AWS_PATH'].'/'.static::$config[ 'filename' ],
      static::get_headers(),
      $content
    );
    return $response;
  }

  /**
   * set_configs Function for setting parameters in the configuration data-set.
   * @param array $configs
   * @return boolean
   */
  public static function set_configs( $configs ){
    try{
      foreach($configs as $k=>$v){
        if( is_array($v) ){
          foreach($v as $_k=>$_v){
            static::$config[ $k ][ $_k ]= $_v;
          }
        }
        static::$config[ $k ]= $v;
      }
    } catch(\Exception $e){
      return false;
    }
    return true;
  }

  protected static function set_filename( $filename ){
    static::$config['filename']= $filename;
  }

  protected static function set_date( $x_amz_date ){
    static::$config['headers']['x-amz-date']= $x_amz_date;
  }

  protected static function set_content_type( $type ){
    static::$config['headers']['content-type']= $type;
  }

  protected static function set_content_length( $content ){
    $length= strlen($content);
    static::$config['headers']['content-length']= $length;
  }

  protected static function set_content_md5( $content ){
    $md5= base64_encode(pack('H*',md5($content)));
    static::$config['headers']['content-md5']= $md5;
  }

  protected static function set_authorization( $string_to_sign ){
    $hash= hash_hmac( 'sha1',$string_to_sign,static::$config['aws_secret_key'],true );
    $signature= base64_encode($hash);
    static::$config['headers']['authorization']= 'AWS '.static::$config['aws_access_key'].':'.$signature;
  }

  protected static function get_string_to_sign(){
    $string_to_sign= static::get_canonicalized_headers()."\n".static::get_canonicalized_amz_headers()."\n".static::get_canonicalized_resource();
    $string_to_sign= "PUT\n".$string_to_sign;
    return $string_to_sign;
  }
  
  protected static function get_canonicalized_headers(){
    $canonicalized_headers= static::$config['headers']['content-md5']."\n".static::$config['headers']['content-type']."\n"/*.static::$config['headers']['date']*/;
    return $canonicalized_headers;
  }

  protected static function get_canonicalized_amz_headers(){
    $x_amz= function($array){
      $x_amz_= array();
      foreach($array as $header=>$value){
        if( (bool)preg_match('/(x-amz)/i',$header) ){
          $x_amz_[]= strtolower($header).':'.$value;
        }
      }
      asort($x_amz_);
      return implode("\n",$x_amz_);
    };
    $canonicalized_amz_headers= $x_amz( static::$config['headers'] );
    return $canonicalized_amz_headers;
  }
  
  protected static function get_canonicalized_resource(){
    $canonicalized_resource= '/'.static::$config['bucket'].$_ENV['AWS_PATH'].'/'.static::$config['filename'];
    return $canonicalized_resource;
  }

  protected static function get_headers(){
    $headers= array_keys(static::$config['headers']);
    $result_set= array();
    foreach( $headers as $key ){
      $result_set[]= $key.':'.static::$config['headers'][ $key ];
    }
    return $result_set;
  }  
  //  
}