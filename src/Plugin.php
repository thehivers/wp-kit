<?php

namespace OhLabs\WPKit;

class Plugin
{
  use \OhLabs\WPKit\Traits\Singleton;
  
  /**
   * Get plugin directory
   * @since 1.0.0
   */
  public static function dir ($path='')
  {
    static $base; if (!$base)
    { $base = WP_PLUGIN_DIR . '/' . static::NAME; }
    return empty($path) ? $base : "$base/$path";
  }
  
  /**
   * Get plugin url
   * @since 1.0.0
   */
  public static function url ($path='')
  {
    static $base; if (!$base)
    { $base = WP_PLUGIN_URL . '/' . static::NAME; }
    return empty($path) ? $base : "$base/$path";
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
