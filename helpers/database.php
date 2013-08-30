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
    if( !(bool)preg_match('/(^INSERT)|(^SELECT)|(^UPDATE)|(^DELETE).*/i', $query) )
      return false;
    if( static::connect()!==true )
      return false;       
    $result= mysqli_query( static::$db,$query );
    // check if any results are available
    if( (bool)preg_match('/^INSERT.*/i', $query) ){
      return mysqli_insert_id( static::$db);
    } else if( (bool)preg_match('/^UPDATE.*/i', $query) ){
      return mysqli_store_result( static::$db );
    } else if( (bool)preg_match('/^DELETE.*/i', $query) ){
      return mysqli_store_result( static::$db );
    }
    // convert results into json format
    $rows = array();
    if( gettype($result)!=="boolean" ){
      while($row= mysqli_fetch_assoc($result))
        $rows[]= $row;
      return empty($rows) ? false:json_encode($rows);
    }

    return $result;
  }

}

?>