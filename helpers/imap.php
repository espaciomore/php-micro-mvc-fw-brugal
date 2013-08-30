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
    static::$imap= imap_open(
      static::$config['mailbox'], 
      static::$config['username'], 
      static::$config['password']
      );
    return static::$imap ? true:false;
  }

  /**
   * disconnect Function for disconnecting from the mailbox.
   * @return boolean
   */
  protected static function disconnect(){
    if( !static::$imap )
      return false;
    return imap_close( static::$imap );
  }

  /**
   * imap_search_mails Function for retreiving mails from mailbox.
   * @param string $criteria
   * @return array
   */
  protected static function imap_search_mails( $criteria ){
    static::$mails= imap_search( static::$imap,$criteria );
  }

  /**
   * fetch_overview Function for fetching mail from mailbox.
   * @param string $sequence
   * @return array
   */
  protected static function fetch_overview( $sequence ){
    if( !static::$imap )
      return false;
    $result= imap_fetch_overview( static::$imap,$sequence );
    return $result;
  }

  /**
   * fetch_body Function for fetching mail content and decoding properly.
   * @param object $msg_number
   * @return string
   */ 
  protected static function fetch_body( $msg_number ){
    // TEXT / HTML
    $type_msg= imap_fetchbody( static::$imap,$msg_number,1.2 );
    if( $type_msg==='' ){
      // TEXT / PLAIN
      $type_msg= imap_fetchbody( static::$imap,$msg_number,1.1 );
      if( $type_msg==='' ){
        // body-text
        $type_msg= imap_fetchbody( static::$imap,$msg_number,1.0 );
      }      
    }
    if ( $msg=base64_decode($type_msg,true) ){
      $type_msg= $msg;
    }
    $content= quoted_printable_decode($type_msg);
    $content= urldecode($content);

    return $content;
  }

}

?>