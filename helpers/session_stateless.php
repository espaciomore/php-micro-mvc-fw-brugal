<?php 

namespace Helpers;

/**
 * Session_Stateless Class is intended to use store no cookies.
 *
 * By creating a stateless session one can handle request without
 * needing to save any user data in the server or the client.
 */
class Session_Stateless implements ISession {

  /**
   * is_alive Function for knowing if the session is present and has timed out.
   * @return boolean
   */
  public static function is_alive(){
    if ( !static::exists() ){
      return false;
    }
    $json_str = \Helpers\Convertion::decode_user_data($_REQUEST['stateless_session']);
    $session = json_decode( $json_str );
    $expired = isset($session->timeout) && (time() < \Helpers\Convertion::time_decode($session->timeout));
    
    return $expired;
  }

  /**
   * exists Function for knowing if the session is present.
   * @return boolean
   */
  public static function exists(){
    return isset($_REQUEST['stateless_session']);
  }

  /**
   * get_session Funtion for getting all the session data.
   * @return array
   */
  public static function get_session(){
    return $_SESSION['stateless_session'];
  }

  /**
   * log_in Function for creating user session data.
   * @param object(stdClass) $user
   * @return boolean
   */
  public static function log_in( $user ){
    if( isset($_SESSION['stateless_session']['user_email']) && $_SESSION['stateless_session']['user_email']!==$user->email ){
      return false;
    }
    $_SESSION['stateless_session']['timeout'] = \Helpers\Convertion::time_encode(''.(time()+7200));
    $_SESSION['stateless_session']['user_email'] = $user->email;
    return true;
  }

  /**
   * log_out Function for destroying user session data. 
   * @return boolean
   */
  public static function log_out() {
    $timeout = (time()-60);
    $expired = \Helpers\Convertion::time_encode(''.$timeout);
    unset($_SESSION['stateless_session']);
    $_SESSION['stateless_session']= array('timeout' => $expired);
    return true;
  }

  /**
   * load_session Function for loading the user session data if there is any.
   * @return array
   */
  public static function load_session(){
    if(!static::exists()){
      $_SESSION['stateless_session'] = array();
      return $_SESSION['stateless_session'];
    }
    $json_str= \Helpers\Convertion::decode_user_data($_REQUEST['stateless_session']);
    $session= \Helpers\Convertion::toStdClass( $json_str );    
    foreach ($session as $key => $value) {
      $_SESSION['stateless_session'][$key] = $value;
    }
    $_SESSION['stateless_session']['page'] = isset($_REQUEST['page']) ? $_REQUEST['page']:'0';
    return $_SESSION['stateless_session'];
  }

  /**
   * save_session Function for saving current session data.
   * @param string $page
   * @return boolean
   */
  public static function save_session( $page='' ){
    $_SESSION['stateless_session']['timeout']= \Helpers\Convertion::time_encode(''.(time()+7200));
    $user_data= \Helpers\Convertion::encode_user_data( $_SESSION['stateless_session'] );
    $page= str_replace('value="stateless_session"', "value=\"{$user_data}\"", $page); 

    return $page!=='' ? $page:true;
  }

  /**
   * update_session Function for updating session data.
   * @param array $data
   * @return array
   */
  public static function update_session( $data ){
    foreach ($data as $key => $value) {
      if ($value) {
        $_SESSION['stateless_session'][$key] = $value;
      } else {
        unset($_SESSION['stateless_session'][$key]);
      }
    }
    $_SESSION['stateless_session']['timeout'] = \Helpers\Convertion::time_encode(''.(time()+7200));
    return $_SESSION['stateless_session'];
  }
}