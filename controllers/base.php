<?php 

namespace Controllers;

/**
 * Base Class is the parent class for every controller.
 *
 * By creating a controller object, the instance will have indirect
 * access to models and views.
 */
abstract class Base {

  /**
   * @var string $name : map to this name.
   */
  protected static $name;
  /**
   * @var boolean $displayable :  when used as hyperlink.
   */  
  protected static $displayable = false;
  /**
   * @var int $menu_index :  give a position in menus.
   */  
  public static $menu_index = 0;
  /**
   * @var object $data : saved data as stdClass object.
   */  
  protected static $data;
  /**
   * @var string $html : saved page content.
   */  
  protected static $html;

  /**
   * update Function for updating a unique resource.
   * @param array $session
   * @param array $request   
   * @return boolean
   */
  public static function update( $session,$request ){
    static::$data = \Models\Base::update( $identifier,$session );
    return false;
  } 

  /**
   * create Function for creating a new resource.
   * @param array $session
   * @param array $request   
   * @return boolean
   */
  public static function create( $session,$request ){
    static::$data = \Models\Base::create( $session );
    return false;
  } 

  /**
   * delete Function for deleting a unique resource.
   * @param array $session
   * @param array $request   
   * @return boolean
   */
  public static function delete( $session,$request ){
    static::$data = \Models\Base::delete( $identifier,$session );
    return false;
  } 

  /**
   * get_one Function for unique resource look up.
   * @param string $identifier
   * @param array $session
   * @param array $request
   * @return object(stdClass)
   */
  public static function get_one( $identifier,$session,$request ){
    static::$data = \Models\Base::get_one( $identifier,$session );
    return static::$data;
  }

  /**
   * get_list Function for resources look up.
   * @param array $session
   * @param array $request   
   * @return object(stdClass)
   */
  public static function get_list( $session,$request ){
    static::$data = \Models\Base::get_list( $session );
    return static::$data;
  }

  /**
   * get_content Function for getting HTML content.
   * @param array $session
   * @param array $request   
   * @param string $html
   * @return string
   */
  public static function get_content( $session,$request,$html='' ){
    $session['request'] = $request;
    switch ($html) {
      case 'redirect':
        static::$html = static::$html . \Views\Redirect::render_content( static::$data,$session ); 
        break;
      default:
        static::$html = static::$html . \Views\Base::render_content( static::$data,$session ); 
        break;
    }   
    return static::$html;
  }

  /**
   * get_name Function for getting $name.
   * @return string
   */
  public static function get_name(){
    if(!static::$name){
      return strtolower( str_replace('Controllers\\', '', get_called_class()));
    }
    return static::$name;
  }

  /**
   * get_data Function for getting $data.
   * @return object(stdClass)
   */
  public static function get_data(){
    return static::$data;
  }
  
  /**
   * get_displayable Function for getting $displayable.
   * @return boolean
   */
  public static function is_displayable(){
    return static::$displayable;
  }

}//