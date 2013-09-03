<?php 

namespace Configs;

/**
 * Dev Class contains the necessary configuration for development.
 *
 * By exposing the method within this class, one can import settings
 * that would be needed in the development environment.
 */
abstract class Dev {

  /**
   * @var array $messages : contains some information to be displayed.
   */
  public static $messages = array();

  /**
   * setup Function for setting configurations at the beginning.
   * @return void
   */
  public static function setup(){
    $_ENV['PROTOCOL'] = 'http';
    // echo phpinfo();
    // environment error reporting
    if ( isset($_ENV['DEBUG']) ){
      ini_set('error_reporting', -1);
      ini_set('display_errors', 1);
      ini_set('html_errors', 1);
    }
    // environment memory usage
    $current_memory_limit = get_cfg_var('memory_limit');
    $memory_limit = '128M';
    static::$messages[] = ( ini_set('memory_limit', $memory_limit) ? 'Memory Limit: '.$memory_limit:'Memory Limit: '.$current_memory_limit );
    // environment runtime vars
    $current_max_execution_time = get_cfg_var('max_execution_time');
    $max_execution_time = 120;
    static::$messages[] = ( ini_set('max_execution_time', $max_execution_time) ? 'Max Execution Time: '.$max_execution_time:'Max Execution Time: '.$current_max_execution_time );    
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
    static::$messages[] = ( 'Memory Usage: '.number_format( memory_get_usage() ) );
    static::$messages[] = ( 'Memory Peak Usage: '.number_format( memory_get_peak_usage() ) );
    static::puts( implode(', ',static::$messages ) );
  }

  /**
   * puts Function for echoing information (only in debug mode)
   * @param string $output_str
   * @return void
   */
  public static function puts( $output_str ){
    if ( isset($_ENV['DEBUG']) )
      echo $output_str;
  }
}
