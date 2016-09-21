<?php

namespace OhLabs\WPKit;

class Options
{
  use \OhLabs\WPKit\Traits\Singleton;
  
  /** @var string Option database id */
  protected $dbkey;
  /** @var array Options defaults */
  protected $default = array ();
  /** @var array Options from database */
  protected $options;
  
  /**
   * The Constructor
   */
  protected function __construct ()
  {
    $this->options = array_merge(
    $this->default,get_option($this->dbkey,array()));
  }
  
  /**
   * Get name for the input element
   */
  public function getName ($key)
  {
    return "{$this->dbkey}[{$key}]";
  }
  
  /**
   * Get ID for the input element
   */
  public function getID ($key)
  {
    return "{$this->dbkey}_{$key}";
  }
  
  /**
   * Get option
   */
  public function get ($key)
  {
    return isset($this->options[$key])
    ? $this->options[$key]
    : null;
  }
  
  /**
   * Set option
   */
  public function set ($key,$value='')
  {
    $this->options[$key] = $value;
    update_option($this->dbkey,$this->options,'yes');
  }
  
  /**
   * Get post value
   */
  public function getPostValue ($key)
  {
    if (!isset($_POST[$this->dbkey])) return null;
    $vals = $_POST[$this->dbkey];
    if (!isset($vals[$key])) return null;
    return $vals[$key];
  }
}
