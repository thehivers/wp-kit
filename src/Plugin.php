<?php

namespace OhLabs\WPKit;

class Plugin
{
  /**
   * Get plugin directory
   * @since 1.0.0
   */
  public static function dir ($path='')
  {
    static $base; if (!$base)
    { $class = get_called_class(); $base = WP_PLUGIN_DIR . '/' . $class::NAME; }
    return $base . $path;
  }
  
  /**
   * Get plugin url
   * @since 1.0.0
   */
  public static function url ($path='')
  {
    static $base; if (!$base)
    { $class = get_called_class(); $base = WP_PLUGIN_URL . '/' . $class::NAME; }
    return $base . $path;
  }
  
  /**
   * Activation function
   * @private
   */
  public function activate ()
  {
    // Must be overriden
  }
  
  /**
   * The Constructor
   */
  protected function __construct ()
  {
    register_activation_hook (self::dir('/index.php'),array($this,'activate'));
  }
}
