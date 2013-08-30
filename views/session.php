<?php

namespace Views;

/**
 * Session Class is the view for /resource/id/session
 *
 * By implementing this view object one can change the html
 * content that the controllers should provide on request.
 */
class Session extends Base {

  /**
   * @var array $templates : loadable content.
   */
  protected static $templates = array(
    'open_html.php',
    'session.php',
    'close_html.php',
  );

} //