<?php
// run this script every 15 minutes
date_default_timezone_set("UTC");
//
$_ENV['ROOT']= dirname(__FILE__);

function __autoload($classname) {
    $parts= explode('\\',$classname);
    $filename= $_ENV['ROOT'].'/'. implode('/', $parts) .'.php';
    if ($parts[1]{0}==='I'){ 
      require(strtolower( $filename));     // interface
    } else { 
      require_once(strtolower( $filename));  // class
    }
}
spl_autoload_register('__autoload');

\Configs\Local::setup();
//
$now= function(){
  $date= strtotime('2013-02-18');
  return $date;
};
//
$mail= new \Helpers\Mailing( array(
  'username'=>'{username}@gmail.com',
  'password'=>'{password}',
  'mailbox'=>'{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX',
  ) 
); 
//
$emails= $mail->get_mailing( "ON ".date('Y-m-d',$now()) );  
//
