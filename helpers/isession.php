<?php

namespace Helpers;

/**
 * ISession Interface is intended for assuring the session behaviour.
 *
 * By implementing this interface, all the session objects will have
 * to respond to specified method calls written in this contract.
 */
interface ISession {

  /**
   * is_alive Function for knowing if the session is present and has timed out.
   * @return boolean
   */
  public static function is_alive();

  /**
   * exists Function for knowing if the session is present.
   * @return boolean
   */	
  public static function exists();

  /**
   * get_session Funtion for getting all the session data.
   * @return array
   */  
  public static function get_session();

  /**
   * log_in Function for creating user session data.
   * @param object(stdClass) $user
   * @return boolean
   */  
  public static function log_in( $user );

  /**
   * log_out Function for destroying user session data. 
   * @return boolean
   */
  public static function log_out();

  /**
   * load_session Function for loading the user session data if there is any.
   * @return array
   */  
  public static function load_session();

  /**
   * save_session Function for saving current session data.
   * @param string $page
   * @return boolean
   */  
  public static function save_session( $page='' );

  /**
   * update_session Function for updating session data.
   * @param array $data
   * @return array
   */  
  public static function update_session( $data );
}