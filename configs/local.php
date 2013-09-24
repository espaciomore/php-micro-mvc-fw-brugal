<?php 

namespace Configs;

/**
 * Local Class contains the necessary configuration for production.
 *
 * By exposing the method within this class, one can import settings
 * that would be needed in the production environment.
 */
abstract class Local {

  /**
   * setup Function for setting configurations at the beginning.
   * @return void
   */
  public static function setup(){
    $_ENV['PROTOCOL'] = 'https';
    // environment memory usage
    ini_set('memory_limit', '128M');
    // environment runtime vars
    ini_set('max_execution_time', 120);
    // when getting the time() or data() it should be in EST – Eastern Standard Time
    date_default_timezone_set('America/New_York');    
    // defining charset for FR language compatibility
    ini_set('default_charset','utf-8');
  }

  /**
   * teardown Function for dumping settings information.
   * @return void
   */  
  public static function teardown(){
    //
  }

}