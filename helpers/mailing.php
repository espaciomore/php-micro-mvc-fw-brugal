<?php

namespace Helpers;

/**
 * Mailing Class is extending IMAP for connecting and fetch to mailbox.
 * 
 * By instantiating this class one can get mails from a mailbox,
 * and the class will take care of decoding the mail message.
 */
class Mailing extends IMAP {

  private $map= array(
    'text/html'=> 1.2,
    'text/plain'=> 1.1,
    'multipart/alternative'=> 1.2,
    'multipart/related'=> 1.2,
    'multipart/mixed'=> 1.2,
  );
  /**
   * __construct Function instance contructor.
   * @param array $config
   * @return object
   */  
  public function __construct( $config=false ){
    if( $config )
      self::$config = $config;
    return $this;
  }

  /**
   * get_mailing Function for getting the mail from mailbox.
   * @param string $filter
   * @return mixxed
   */    
  public function get_mailing( $filter='' ){
    if( !self::connect() ){
      return false;
    }
    // strip the email content
    $updates = array();
    if( self::imap_search_mails( $filter ) ){
      foreach( self::$mails as $uid ){
        //
        $header_text= self::fetch_header( $uid );
        $header= @imap_rfc822_parse_headers($header_text);
        //
        preg_match('/content-type:\s([\/\w]+)/i', $header_text,$content_type );
        if( !$content_type ){
          $type= 1.0;
        } else {
          $type= $this->map[ strtolower($content_type[1]) ];
        }  
        $content= self::fetch_body( $uid, $type ? $type : 1.0 );
        //
        $info= array(
          'uid'=> $uid,
          'header'=> $this->get_header( $header_text ),
          'content'=> $this->get_content( $content ),
          'sent_date'=> $this->get_date( $header ),
          'subject'=> $this->get_subject( $header ),
          'sender_name'=> $this->get_sender_name( $header ),
          'sender_email'=> $this->get_sender_email( $header ),
        );
        $updates[]= $info;
      }
    }
    // close connection to imap server
    self::disconnect();

    return empty($updates) ? false : $updates;
  }

  /**
   * get_header Function for decoding the mail header.
   * @param string $header
   * @return string
   */  
  private function get_header( $header ){
    return self::escape_param( $header );
  }

  /**
   * get_content Function for decoding the mail content.
   * @param string $content
   * @return string
   */  
  private function get_content( $content ){
    return self::escape_param( $content );
  }

  /**
   * get_subject Function for decoding the mail subject.
   * @param object $header
   * @return string
   */  
  private function get_subject( $header ){
    if( isset($header->{'subject'}) ){
      $subject= $header->{'subject'};
    } else if( isset($header->{'Subject'}) ){
      $subject= $header->{'Subject'};
    } else {
      $subject= '';
    }
    return self::escape_param( $subject );
  }

  /**
   * get_date Function for decoding the mail date.
   * @param object $header
   * @param string $format
   * @return string
   */  
  private function get_date( $header,$format='Y-m-d H:i:s' ){
    if( isset($header->{'date'}) ){
      $date= $header->{'date'};
    } else if( isset($header->{'Date'}) ){
      $date= $header->{'Date'};
    } else {
      $date= '0000-00-00';
    }
    $sent_date= date( $format,strtotime( $date ) );
    return self::escape_param( $sent_date );
  }

  /**
   * get_sender_name Function for decoding the mail sender name.
   * @param object $header
   * @return string
   */  
  private function get_sender_name( $header ){
    $from= $header->{'sender'};
    $name= isset($from[0]->{'personal'}) ? $from[0]->{'personal'} : '';
    return self::escape_param( $name );
  }

  /**
   * get_sender_email Function for decoding the mail sender email.
   * @param object $header
   * @return string
   */  
  private function get_sender_email( $header ){
    $from= $header->{'sender'};
    $email= $from[0]->{'mailbox'}.'@'.$from[0]->{'host'};
    return self::escape_param( $email );
  }

  /**
   * escape_param Function for escaping characters not allowed by charset.
   * @param string $param
   * @return string
   */
  private static function escape_param( $param ){
    $escaped_param= str_replace( '\'','\\\'',str_replace('"','\\"',$param) );
    return $escaped_param;
  } 
}