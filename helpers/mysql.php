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
    $query = 'SELECT '.$fields.' FROM '.$table.( $filter ? ' WHERE '.$filter.';':';');
    return self::execute( $query );
  }

  /**
   * update Function for updating an entry in a table.
   * @param string $fields
   * @param string $table
   * @param string $filter
   * @return string(JSon)
   */
  public function update( $fields, $table, $filter=false ){
    $query = 'UPDATE '.$table.' SET '.$fields.( $filter ? ' WHERE '.$filter.';':';');
    return self::execute( $query );
  }

  /**
   * delete Function for deleting an entry from a table.
   * @param string $table
   * @param string $filter
   * @return boolean
   */
  public function delete( $table, $filter=false ){
    $query = 'DELETE FROM '.$table.( $filter ? ' WHERE '.$filter.';':';');
    return self::execute( $query );
  }

}