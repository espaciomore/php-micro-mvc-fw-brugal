<?php

namespace Helpers;

/**
 * Mailing Class is extending IMAP for connecting and fetch to mailbox.
 * 
 * By instantiating this class one can get mails from a mailbox,
 * and the class will take care of decoding the mail message.
 */
class Mailing extends IMAP {

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
    // get email from inbox
    self::imap_search_mails( $filter );
    // strip the email content
    $updates = array();
    foreach( self::$mails as $mail ){
      $mail_details= self::fetch_overview( $mail );
      $info= array(
        'content'=> $this->get_content( $mail ),
        'sent_date'=> $this->get_date( $mail_details[0]->{'date'} ),
        'subject'=> $mail_details[0]->{'subject'},
        'sender_name'=> $this->get_sender_name( $mail_details[0]->{'from'} ),
        'sender_email'=> $this->get_sender_email( $mail_details[0]->{'from'} ),
      );
      $updates[]= $info;
    }
    // close connection to imap server
    self::disconnect();

    return $updates;
  }

  /**
   * get_content Function for decoding the mail content.
   * @param object $mail
   * @return string
   */  
  private function get_content( $mail ){
    return self::fetch_body( $mail );
  }

  /**
   * get_date Function for decoding the mail date.
   * @param string $date
   * @param string $format
   * @return string
   */  
  private function get_date( $date,$format='Y-m-d H:i:s' ){
    $sent_date= date( $format,time($date) );
    return $sent_date;
  }

  /**
   * get_sender_name Function for decoding the mail sender name.
   * @param string $from
   * @return string
   */  
  private function get_sender_name( $from ){
    $name= trim(preg_replace('/(<.*>)+/', '', $from ));
    return $name;
  }

  /**
   * get_sender_email Function for decoding the mail sender email.
   * @param string $from
   * @return string
   */  
  private function get_sender_email( $from ){
    $email= preg_replace('/[<>]+/','',preg_replace('/^.*\\s/', '', $from ));
    return $email;
  }
}