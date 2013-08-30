<?php

namespace Helpers;

/**
 * Session Class is intended to wrap the user sessions method to be used.
 *
 * By setting up the variable $session one can specify one of two references;
 * an instance of Session_Cookies or Session_Stateless.
 */
abstract class Session implements ISession {

  /**
   * @var object $session : reference to an object.
   */
  public static $session;

  /**
   * is_alive Function for knowing if the session is present and has timed out.
   * @return boolean
   */
  public static function is_alive(){
    return static::$session->is_alive();
  }

  /**
   * exists Function for knowing if the session is present.
   * @return boolean
   */
  public static function exists(){
    return static::$session->exists();
  }

  /**
   * get_session Funtion for getting all the session data.
   * @return array
   */  
  public static function get_session(){
    return static::$session->get_session();
  }

  /**
   * log_in Function for creating user session data.
   * @param object(stdClass) $user
   * @return boolean
   */  
  public static function log_in( $user ){
    return static::$session->log_in( $user );
  }

  /**
   * log_out Function for destroying user session data. 
   * @return boolean
   */
  public static function log_out(){
    return static::$session->log_out();
  }

  /**
   * load_session Function for loading the user session data if there is any.
   * @return array
   */  
  public static function load_session(){
    return static::$session->load_session();
  }

  /**
   * save_session Function for saving current session data.
   * @param string $page
   * @return boolean
   */  
  public static function save_session( $page='' ){
    return static::$session->save_session( $page );
  }

  /**
   * update_session Function for updating session data.
   * @param array $data
   * @return array
   */  
  public static function update_session( $data ){
    return static::$session->update_session( $data );
  }

}//