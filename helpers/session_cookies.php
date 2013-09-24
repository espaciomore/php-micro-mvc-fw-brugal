<?php 

namespace Helpers;

/**
 * Session_Cookies Class is intended to handle user sessions based 
 * on client side cookies storage.
 *
 * Storing cookies in client's devices is a security concern so It's strongly 
 * recommended to use Session_Stateless Class instead.
 */
class Session_Cookies implements ISession {

  /**
   * is_alive Function for knowing if the session is present and has timed out.
   * @return boolean
   */
  public static function is_alive(){
    if ( !static::exists() ){
      return false;
    }
    $object_data= json_decode( $_COOKIE['stateless_session'] );
    $json_str = \Helpers\Convertion::decode_user_data( $object_data->{'data'} );
    $cookie = \Helpers\Convertion::toStdClass( $json_str );
    $expired = isset($cookie->timeout) && (time() < \Helpers\Convertion::time_decode($cookie->timeout));
    
    return $expired;
  }

  /**
   * exists Function for knowing if the session is present.
   * @return boolean
   */
  public static function exists(){
    $cookie= $_COOKIE;
    return isset($cookie['stateless_session']);
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
    $_SESSION['stateless_session']['timeout'] = \Helpers\Convertion::time_encode(''.(time()+43200));
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
      $_SESSION['stateless_session']= array();
      return $_SESSION['stateless_session'];
    }
    $object_data= json_decode($_COOKIE['stateless_session']);
    $json_str= \Helpers\Convertion::decode_user_data( $object_data->{'data'} );
    $cookie= \Helpers\Convertion::toStdClass( $json_str );
    foreach ($cookie as $key => $value) {
      $_SESSION['stateless_session'][$key]= $value;
    }
    return $_SESSION['stateless_session'];
  }

  /**
   * save_session Function for saving current session data.
   * @param string $page
   * @return boolean
   */
  public static function save_session( $page='' ){
    $_SESSION['stateless_session']['timeout']= \Helpers\Convertion::time_encode(''.(time()+43200));
    $user_data= \Helpers\Convertion::encode_user_data( $_SESSION['stateless_session'] );
    setcookie( 'stateless_session', 
      \Helpers\Convertion::toJSon(array("data"=> $user_data)), time()+43200,'/' );   
    return $page;
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
    $_SESSION['stateless_session']['timeout'] = \Helpers\Convertion::time_encode(''.(time()+43200));
    return $_SESSION['stateless_session'];
  }  
}