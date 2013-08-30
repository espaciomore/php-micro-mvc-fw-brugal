<?php

namespace Views;

/**
 * Base Class is the parent class for every view.
 *
 * By creating a view object one can implement the logic
 * for generating html based on templates with dynamic
 * content using a given data model and request data.
 */
abstract class Base {

  /**
   * @var array $templates : loadable content.
   */
  protected static $templates = array(
    'open_html.php',
    'head_content.php',
    'open_body.php', 
    'base.php',           // this would be the main content
    'session.php',
    'close_body.php',
    'close_html.php',
  );
  /**
   * @var string $content : saved html content.
   */
  protected static $content = '';
  /**
   * @var object(stdClass) $data : given data model. 
   */  
  protected static $data;
  /**
   * @var array $session : available session data.
   */
  protected static $session;

  /**
   * session Function for getting the available session data.
   * @param string $key
   * @return object(string)
   */
  public static function session( $key ){
    return isset(static::$session[ $key ]) ? static::$session[ $key ]:false;
  }

  /**
   * render_content Function for auto generating html content.
   * @param array $data
   * @param array $session
   * @param array $templates
   * @return string
   */
  public static function render_content( $data,$session,$templates=false ){
    if($templates){
      static::$templates = $templates;
    }
    static::$data = $data;
    static::$session = $session;
    
    static::load_content();

    return static::$content;
  }

  /**
   * load_content Function for loading templates to $content
   * @return void
   */
  protected static function load_content(){
    foreach (static::$templates as $index => $template) { 
      static::$content = static::$content . static::catch_content( $_ENV['ROOT'].'/views/templates/'.$template );
    }
  }

  /**
   * catch_content Function for capturing script contents.
   * @param string $template
   * @return string
   */
  protected static function catch_content( $template ){
    ob_start();
      try{
        if( eval( '?>'.file_get_contents( $template ).'<?php;')===false ){
          \Helpers\Request_Service::set_response_code(200);
          throw new \Exception("parsing error");
        }
        $this_content = ob_get_contents();
      } catch (\Exception $e) {
        $this_content = 'Loading content for '.$template.' resulted in '.$e->getMessage().', please contact administrator.';
      }
    ob_end_clean(); 
    return $this_content;
  }

  /**
   * clear Function for clearing the current saved content.
   * @return void
   */
  public static function clear(){
    static::$content = '';
  }

} //