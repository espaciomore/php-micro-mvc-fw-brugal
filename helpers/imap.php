<?php

namespace Helpers;

/**
 * IMAP Class is abstract for general mailing reading purposes.
 *
 * By extending this class one can read from any email account mailbox
 * without having to handle connections or content type.
 */
abstract class IMAP {

  /**
   * @var object $imap : maps to mailbox connection.
   */
  protected static $imap= null;
  /**
   * @var object $emails : maps to fetched mail messages.
   */
  protected static $mails= array();
  /**
   * @var array $config : maps to connection parameters.
   */
  protected static $config= array(
    'mailbox'=> 'mailbox',
    'username'=> 'username',
    'password'=> 'password',
    );

  /**
   * connect Function for connecting to the mailbox.
   * @return boolean
   */
  protected static function connect(){
    $connect= function($config){
      set_time_limit(60);
      $imap= @imap_open(
        $config['mailbox'], 
        $config['username'], 
        $config['password']
      );
      return $imap;
    };
    static::$imap= $connect( static::$config );

    return static::$imap ? true:false;
  }

  /**
   * disconnect Function for disconnecting from the mailbox.
   * @return boolean
   */
  protected static function disconnect(){
    if( !static::$imap )
      return false;
    $close= function($imap){
      set_time_limit(60);
      $close= @imap_close($imap);
      return $close;
    };
    return $close(static::$imap);
  }

  /**
   * imap_search_mails Function for retreiving mails from mailbox.
   * @param string $criteria
   * @return boolean
   */
  protected static function imap_search_mails( $criteria,$option=SE_UID ){
    $search= function($imap,$criteria,$option){
      set_time_limit(60);
      $search= @imap_search( $imap,$criteria,$option );
      return $search;
    };
    static::$mails= $search( static::$imap,$criteria,$option );
    return static::$mails ? true : false; 
  }

  /**
   * fetch_overview Function for fetching mail from mailbox.
   * @param int $uid
   * @return array
   */
  protected static function fetch_overview( $uid,$option=FT_UID ){
    if( !static::$imap )
      return false;
    $fetch= function($imap,$uid,$option){
      set_time_limit(60);
      $fetch= @imap_fetch_overview( $imap,$uid,$option );
      return $fetch;
    };
    $result= $fetch( static::$imap,$uid,$option );
    return $result;
  }

  /**
   * fetch_header Function for fetching mail from mailbox.
   * @param int $uid
   * @return array
   */
  protected static function fetch_header( $uid,$option=FT_UID ){
    if( !static::$imap )
      return false;
    $fetch= function($imap,$uid,$option){
      set_time_limit(60);
      $fetch= @imap_fetchheader( $imap,$uid,$option );
      return $fetch;
    };
    $result= $fetch( static::$imap,$uid,$option );
    return $result;
  }

  /**
   * fetch_body Function for fetching mail content and decoding properly.
   * @param int $uid
   * @return string
   */ 
  protected static function fetch_body( $uid,$type,$option=FT_UID ){
    $body= function($imap,$uid,$type,$option){
      set_time_limit(60);
      $body= @imap_fetchbody( $imap,$uid,$type,$option );
      return $body;
    };
    $type_msg= $body( static::$imap,$uid,$type,$option );
    if ( $msg= @base64_decode($type_msg,true) ){
      $type_msg= $msg;
    }
    $content= $type_msg;

    return $content;
  }

}

?>