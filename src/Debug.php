<?php

namespace OhLabs\WPKit;

class Debug
{
  /**
   * Write arguments to error_log
   * @since 1.0.0
   */
  public static function log ()
  {
    $args = func_get_args();
    foreach ($args as $arg):
    if (is_array($arg) || is_object($arg))
    error_log(print_r($arg,true));
    else
    error_log($arg);
    endforeach;
  }
  
  /**
   * Write arguments to output
   * @since 1.0.0
   */
  public static function display ()
  {
    $args = func_get_args();
    echo '<code><pre>';
    foreach ($args as $arg):
    if (is_array($arg) || is_object($arg))
    echo print_r($arg,true);
    else
    echo $arg;
    endforeach;
    echo '</pre></code>';
  }
}
