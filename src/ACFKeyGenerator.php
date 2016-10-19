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
  public function generate ($name)
  {
    return $base . '_' . $name;
  }
}
