<?php

// defined environment variables in the scope of this application.
$_ENV['ROOT'] = dirname(__FILE__);

/** 
 * autoload Function for auto loading scripts from class names.
 * @param string $classname
 * @return void
 */
function __autoload($classname) {
    $parts = explode('\\',$classname);
    $filename = $_ENV['ROOT'].'/'. implode('/', $parts) .'.php';
    if ($parts[1]{0}==='I'){ 
      require(strtolower( $filename));     // interface
    } else { 
      require_once(strtolower( $filename));  // class
    }
}
spl_autoload_register('__autoload');


/**
 * requested_content Function for getting and/or processing all requests.
 * @return string
 */
function requested_content() {
  try{
    // Load proper environment configurations
    \Configs\Dev::setup();
    \Helpers\Routing::init();
    $page = \Helpers\Routing::get_content();
  } catch (\Exception $e){
    \Configs\Dev::teardown();
  }
  return $page ? $page:'';
} //

echo requested_content();
