<?php

namespace OhLabs\WPKit\Traits;

trait ObjectProps
{
  /**
   * Name to ID (whitespace to underscodes, and all lowercase)
   */
  public static function toID ($name)
  {
    return strtolower(preg_replace('/\s+/','_',$name));
  }

  /**
   * Singular to plural name
   */
  public static function toPlural ($name)
  {
    return $name[strlen($name)-1] == 'y'
    ? (substr($name,0,-1) . 'ies')
    : $name . 's';
  }
}
