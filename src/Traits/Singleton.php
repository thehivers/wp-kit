<?php

namespace OhLabs\WPKit\Traits;

trait Singleton
{
  public static function instance ()
  {
    static $instance; $class    = get_called_class();
    if   (!$instance) $instance = new $class;
    return $instance;
  }
}
