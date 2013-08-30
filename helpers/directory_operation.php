<?php

namespace Helpers;

/**
 * Directory_Operation Class is meant to execute directory tasks.
 *
 * With theses helper methods it will be possible to read, create, modify
 * or rename files.
 */
abstract class Directory_Operation{

  /**
   * get_list Function for getting the list of available controllers.
   * @param string $dir
   * @return array
   */
  public static function get_list( $dir='./controllers'){ 
    $list = array(); 
    if($handler = opendir($dir)) { 
      while (($sub = readdir($handler)) !== FALSE) { 
        if ($sub != "." && $sub != "..") { 
          if(is_file($dir."/".$sub)) { 
            if($sub !== 'base.php'){
                $list[] = str_replace('.php', '', $sub); 
            }
          }
        } 
      }    
      closedir($handler); 
    } 
    return $list;    
  } 
}