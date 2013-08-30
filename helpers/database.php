<?php 

namespace Helpers;

/**
 * Database Class is abstract for general databse purposes.
 *
 * By extending this class one can query databses without having
 * to handle connections or results set.
 */
abstract class Database {

  /**
   * @var object $db : maps to databse connection.
   */
  protected static $db= null ;
  /**
   * @var array $config : maps to connection parameters.
   */
  protected static $config= array(
    'hostname'=> 'localhost',
    'username'=> 'newsreader',
    'password'=> 'password',
    'database'=> 'newsletters',
    );

  /**
   * connect Function for connecting to database.
   * @return boolean
   */
  private static function connect(){
    static::$db= mysqli_connect(
      static::$config['hostname'],
      static::$config['username'],
      static::$config['password'],
      static::$config['database']
      );
    return static::$db ? true:false;
  }

  /**
   * disconnect Function for disconnecting database.
   * @return boolean
   */
  private static function disconnect(){
    if( !static::$db )
      return false;
    return mysqli_close( static::$db );
  }

  /**
   * execute Function for executing the query string on database.
   * @param string $query
   * @return mixxed
   */
  public static function execute( $query ){
    if( static::connect()!==true )
      return false;   
    // get results from $query
    $result= mysqli_query( static::$db, $query ) or mysqli_connect_error();
    // check if any results are available
    if( gettype($result)==="boolean" )
      return $result;
    if( mysqli_num_rows($result)==0 )
      return '[]';
    // convert results into json format
    $rows = array();
    while($row= mysqli_fetch_assoc($result)){
      $rows[]= $row;
    }
    return json_encode($rows);
  }
}

?>