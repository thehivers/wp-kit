<?php

namespace OhLabs\WPKit\Traits;

trait Singleton
{
  /**
   * Enforces singleton pattern,
   * keeping the constructor private
   * @since 1.0.0
   */
  public static function instance ()
  {
    static $instance; $class    = get_called_class();
    if   (!$instance) $instance = new $class;
    return $instance;
  }
}
