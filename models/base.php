<?php

namespace Models;

/**
 * Base Class is the parent class for every model.
 *
 * By creating a model object one can implement the logic
 * for retrieving data from API.
 */
abstract class Base {

   /**
    * @var object(stdClass) $data : saved call response. 
    */
   public static $data;

   /**
    * update Function for updating a unique resource.
    * @param string $identifier
    * @param array $session
    * @param array $params      
    * @return object(stdClass)
    */
   public static function update( $identifier,$session,$params=array() ){
    static::$data = \Helpers\Convertion::toStdClass('[]');
    return static::$data;
   } 

   /**
    * create Function for creating a new resource.
    * @param array $session
    * @param array $params      
    * @return object(stdClass)
    */
   public static function create( $session,$params=array() ){
    static::$data = \Helpers\Convertion::toStdClass('[]');
    return static::$data;
   } 

   /**
    * delete Function for deleting a unique resource.
    * @param string $identifier
    * @param array $session
    * @param array $params      
    * @return object(stdClass)
    */
   public static function delete( $identifier,$session,$params=array() ){
    static::$data = \Helpers\Convertion::toStdClass('[]');
    return static::$data;
   } 

   /**
    * get_one Function for unique resource look up.
    * @param string $identifier
    * @param array $session
    * @param array $params      
    * @return object(stdClass)
    */
   public static function get_one( $identifier,$session,$params=array() ){
    static::$data = \Helpers\Convertion::toStdClass('[]');
    return static::$data;
   } 

   /**
    * get_list Function for resources look up.
    * @param array $session
    * @param array $params      
    * @return object(stdClass)
    */
   public static function get_list( $session,$params=array() ){
    static::$data = \Helpers\Convertion::toStdClass('[]');
    return static::$data;
   } 
} //
