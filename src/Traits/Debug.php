<?php

namespace OhLabs\WPKit\Traits;

trait Debug
{
  /**
   * Write arguments to error_log
   * @since 1.0.0
   */
  public static function log ()
  {
    $args = func_get_args();
    $name = get_called_class();
    foreach ($args as $arg):
    if (is_array($arg) || is_object($arg))
    error_log("{$name}: " . print_r($arg,true));
    else
    error_log("{$name}: " . $arg);
    endforeach;
  }

  /**
   * Write arguments to output
   * @since 1.0.0
   */
  public static function dump ()
  {
    $args = func_get_args();
    echo '<code><pre>';
    $name = get_called_class();
    echo "{$name}:\n";
    foreach ($args as $arg):
    if (is_array($arg) || is_object($arg))
    echo print_r($arg,true);
    else
    echo $arg;
    endforeach;
    echo '</pre></code>';
  }
}
