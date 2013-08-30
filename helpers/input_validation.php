<?php 

namespace Helpers;

/**
 * Input_Validation Class is used to validate user data.
 *
 * By implementing the method validate, one can choose
 * between the preset rules or patterns; id, email, name.
 */
abstract class Input_Validation {

  /**
   * @var array $patterns : list of pattern for each type of data.
   */
  public static $patterns = array(
    'campaign' => "/^https?:\/\/[^\/]+\/[a-z]\/[0-9]{1,10}\/[A-Za-z0-9]{32,64}$/",
    'name' => "/^([a-zA-Z0-9_ -.*!()]{1,64})$/",
    'email' => "/^([a-zA-Z0-9_\-.+]{1,64})@([a-zA-Z0-9\-_]+\\.)+([a-zA-Z]{2,3})$/",
    'id' => "/^([0-9]{1,10})$/",
  );
  /**
   * @var array $max_lengths : list of max length allowed.
   */
  public static $max_lengths = array(
    'name' => 64,
    'email' => 320, // (64)@(255)
    'id' => 10,
  );

  /**
   * validate Function for validating data by type.
   * @param string $type
   * @param string $data
   * @return boolean
   */
  public static function validate( $type,$data ){
    if ($pattern = static::$patterns[ $type ]){
      if ($max_len = static::$max_lengths[ $type ]){   
        return strlen($data)<=$max_len && (bool)preg_match( $pattern, $data);
      }
    }
    return false;
  }
}//
