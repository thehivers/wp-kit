<?php

namespace OhLabs\WPKit;

class ACFKeyGenerator
{
  private $base;

  /**
   * The Constructor
   */
  public function __construct ($base)
  {
    $this->base = $base;
  }

  /**
   * Generate new id
   */
  public function generate ($suffix,$prefix='')
  {
    return $prefix . '_' . $this->base . '_' . $suffix;
  }
}
