<?php

namespace Views;

/**
 * Redirect Class is not a view but an auto submit form for redirecting.
 *
 * By implementing this view object one can change the html
 * content that the controllers should provide on request.
 */
class Redirect extends Base {

  /**
   * @var array $templates : loadable content.
   */
  protected static $templates = array(
    'open_html.php',
    'js_redirect.php',           // this would be the main content
    'close_html.php',
  );

} //