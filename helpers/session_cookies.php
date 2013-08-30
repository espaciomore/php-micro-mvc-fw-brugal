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
    $cookie = \Helpers\Convertion::toStdClass($_COOKIE['stateless_session']);
    $expired = isset($cookie->timeout) && (time() < \Helpers\Convertion::time_decode($cookie->timeout));
    
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
    return $_COOKIE['stateless_session'];
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
    $params = array();
    foreach ($_SESSION['stateless_session'] as $key => $value) {
      $params[$key] = $value;
    }
    $user->timeout = \Helpers\Convertion::time_encode(''.(time()+7200));
    foreach ($user as $key => $value) {
      $params[$key] = $value;
    }
    setcookie( 'stateless_session', 
          \Helpers\Convertion::toJSon( $params ) );
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
    $_SESSION['stateless_session'] = array('timeout' => $expired);    
    setcookie( 'stateless_session',  
          \Helpers\Convertion::toJSon(array('timeout' => $expired)), $timeout );
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
    $cookie = $_COOKIE['stateless_session'];
    foreach ($cookie as $key => $value) {
      $_SESSION['stateless_session'][$key] = $value;
    }
    return $_SESSION['stateless_session'];
  }

  /**
   * save_session Function for saving current session data.
   * @param string $page
   * @return boolean
   */
  public static function save_session( $page='' ){
    $params = array();
    $params['timeout'] = \Helpers\Convertion::time_encode(''.(time()+7200));
    foreach ($_SESSION['stateless_session'] as $key => $value) {
      $params[$key] = $value;
    }
    setcookie( 'stateless_session', 
          \Helpers\Convertion::toJSon( $params ) );   
    return $page;
  }

  /**
   * update_session Function for updating session data.
   * @param array $data
   * @return array
   */
  public static function update_session( $data ){
    foreach ($data as $key => $value) {
      $_SESSION['stateless_session'][$key] = $value;
    }
    $_SESSION['stateless_session']['timeout'] = \Helpers\Convertion::time_encode(''.(time()+7200));
    return $_SESSION['stateless_session'];
  }  
}