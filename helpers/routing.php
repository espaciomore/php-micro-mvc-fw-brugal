<?php

namespace Helpers;

/**
 * Routing Class provides the routing between controllers, and promotes
 * session control.
 *
 * By initiating a routing instance one can start up the request handling
 * process automatically then one can get any generated content unless any
 * redirect was made as this action will happen right away.
 */
abstract class Routing {
  
  /**
   * @var array $routes : available controllers.
   */
  protected static $routes = array();
  /**
   * @var string $content : saved genereted content.
   */
  protected static $content = '';

  /**
   * init Function for auto initiate the request handling process.
   * @return void
   */ 
  public static function init(){
    static::load_controllers(); 
    static::session_load(); 
    static::request_handle();
    static::session_save();
  }

  /**
   * load_controllers Function for creating the list of available controllers.
   * @return void
   */
  protected static function load_controllers(){
    foreach ( ($c = \Helpers\Directory_Operation::get_list()) as $index => $name) {
      $class_name = \Helpers\Convertion::toClassName( $name );
      $controller = \Helpers\Convertion::get_instance( "\\Controllers\\{$class_name}" );
      static::add_route( "/".strtolower($class_name), $controller );
    }
  }
  
  /**
   * session_load Function for loading session data if there is any.
   * @return void
   */
  protected static function session_load(){
    $session_class =  '\\Helpers\\Session_Stateless';
    \Helpers\Session::$session = new $session_class;
    \Helpers\Session::load_session();
  }

  /**
   * request_handle Function for handling the current request.
   * @return void
   */
  protected static function request_handle(){

    $request_url = \Helpers\Request_Service::get_request_uri();
    $request_data = \Helpers\Request_Service::get_request_params();
    \Helpers\Session::update_session( \Helpers\Request_Service::get_request_data( $request_data ) );

    if ( $_ENV['PROTOCOL']==='https' ){
      header("Strict-Transport-Security: max-age=3600; includeSubDomains");
      if ( \Helpers\Request_Service::get_request_protocol()==='http' ){
        \Helpers\Request_Service::set_response_code( 301 );
        exit();
      }
    }
    if ($request_url['resource_type']==='logout') {
      \Helpers\Session::log_out();
      static::js_redirect('/login');
    }
    if ( !\Helpers\Session::is_alive() && $request_url['resource_type']!=='login'){ 
      static::js_redirect( 
        '/login',
        array( 
          'last_visit' => isset(static::$routes[ "/{$request_url['resource_type']}" ]) ? "/{$request_url['resource_type']}":"/home",
          ) 
        );
    } else if (!$controller = static::$routes[ "/{$request_url['resource_type']}" ]){ 
      $controller = static::$routes['/login']; 
      static::$content = static::$content . $controller->get_content( \Helpers\Session::get_session(), $request_data );
    } else {
      $identifier = $request_url['identifier'];
      if ( $identifier ){
        $controller->get_one( $identifier, \Helpers\Session::get_session() ,$request_data );
        if ( $request_url['view'] ){
          static::$content = static::$content . $controller->get_content( \Helpers\Session::get_session(), $request_data, $request_url['view'] );
        } else {
          static::$content = static::$content . \Helpers\Convertion::toJSon( $controller->get_data() );
        }  
      } else {
        $session_data = \Helpers\Session::get_session();
        $request_type = \Helpers\Request_Service::get_request_type();
        // requests like POST, PUT, DELETE always redirect to GET internally.
        if ( $request_type==="POST" ){
          $controller->create( $session_data,$request_data ); 
        } else if ( $request_type==="PUT" ){
          $controller->update( $session_data,$request_data ); 
        } else if ( $request_type==="DELETE" ){
          $controller->delete( $session_data,$request_data ); 
        }
        // a missing request type will return session information.
        if ( $request_type===false && $request_url['resource_type']!=='login'){
          static::$content = static::$content . \Views\Session::render_content( array(),array() );
        } else {
          $controller->get_list( \Helpers\Session::get_session(),$request_data );
          static::$content = static::$content . $controller->get_content( \Helpers\Session::get_session(), $request_data );
        }
      }
    }
  }

  /**
   * session_save Function for inserting the session stateless data into content.
   * @return void
   */
  protected static function session_save(){
    static::$content = \Helpers\Session::save_session( static::$content );
  }

  /**
   * redirect Function for activating http redirect then exiting php parser.
   * @param string $service_and_action
   * @param array $params
   * @return void
   */
  public static function redirect( $service_and_action, $params=array() ){
    if (empty($params)){
      $session_data = \Helpers\Session::get_session();
      array_push($params, 'stateless_session='. \Helpers\Convertion::encode_user_data( $session_data ) );
    } 
    $param = $params ? '?'.implode('&', $params) : '';
    $server = \Helpers\Request_Service::get_server_name();
    header("Location: {$server}{$service_and_action}{$param}");    
    exit();
  }

  /**
   * js_redirect Function for sending back an auto redirecting script as body.
   * @param string $service_and_action
   * @param array $params
   * @return void
   */
  public static function js_redirect( $service_and_action, $params=array() ){
    $server = \Helpers\Request_Service::get_server_name();
    $session = array(
      'redirect' => "{$server}{$service_and_action}",
      );
    static::$content = static::$content . \Controllers\Base::get_content( $session,$params,'redirect' ); 
    static::session_save();
    echo static::$content;  
    exit();
  }

  /**
   * add_route Function for adding controllers to the list of controllers.
   * @param string $name
   * @param string $controller
   * @return void
   */
  public static function add_route( $name, $controller ){
    static::$routes[$name] = $controller;
  }

  /**
   * get_content Function for getting the generated content.
   * @return string
   */
  public static function get_content(){
    return static::$content;
  }

  /**
   * get_controllers Function for getting the controllers list.
   * @return array
   */
  public static function get_controllers( $ordered=false ){
    if ( !$ordered )
      return static::$routes;
    $in_order = array();
    $routes = static::$routes;
    foreach ($routes as $name => $controller) {
      if ( $controller->is_displayable() )
        $in_order[ $controller::$menu_index ] = array( $name => $controller );
    }
    ksort( &$in_order );
    return $in_order;
  } 
}