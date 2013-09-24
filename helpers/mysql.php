<?php

namespace Helpers;

/**
 * MySQL Class is extending Database but it's MySQL specific.
 * 
 * By instantiating this class one can query a MySQL database
 * using CRUD model.
 */
class MySQL extends Database {
  
  /**
   * __construct Function instance contructor.
   * @param array $config
   * @return object
   */
  public function __construct( $config=false ){
    if( $config )
      self::$config = $config;
    return $this;
  }

  /**
   * create Function for creating an entry in a table.
   * @param string $fields
   * @param string $table
   * @return boolean
   */
  public function create( $fields, $table ){
    $query = 'INSERT INTO '.$table.' VALUES ('.$fields.');';
    return self::execute( $query );
  }

  /**
   * read Function for reading an entry from a table.
   * @param string $fields
   * @param string $table
   * @param string $filter
   * @return string(JSon)
   */
  public function read( $fields, $table, $filter=false ){
    $query = 'SELECT /* SQL_NO_CACHE */ '.$fields.' FROM '.$table.( $filter!==false ? ' '.$filter.';':';');
    $result= self::execute( $query );
    if( (bool)preg_match('/^SELECT \/\* SQL_NO_CACHE \*\/ COUNT.*/i', $query) ){
      return $result[0]['count'];
    }
    return $result;
  }

  /**
   * update Function for updating an entry in a table.
   * @param string $fields
   * @param string $table
   * @param string $filter
   * @return string(JSon)
   */
  public function update( $fields, $table, $filter=false ){
    $query = 'UPDATE '.$table.' SET '.$fields.( $filter!==false ? ' '.$filter.';':';');
    return self::execute( $query );
  }

  /**
   * delete Function for deleting an entry from a table.
   * @param string $table
   * @param string $filter
   * @return boolean
   */
  public function delete( $table, $filter=false ){
    $query = 'DELETE FROM '.$table.( $filter!==false ? ' '.$filter.';':';');
    return self::execute( $query );
  }

}