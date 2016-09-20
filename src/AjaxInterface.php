<?php

namespace OhLabs\WPKit;

class AjaxInterface
{
  use \OhLabs\WPKit\Traits\Singleton;
  
  /** @var array Response messages (code => message) */
  protected $responses = array (100 => 'All good.');
  
  /**
   * Initialize by handling request
   */
  public static function doHandle ()
  {
    static::instance()->handle();
  }
  
  /**
   * Handle request
   */
  public function handle ()
  {
    $this->end(100);
  }
  
  /**
   * Get post value
   */
  public function postVal ($name)
  {
    if (isset($_POST[$name])) return $_POST[$name];
    return null;
  }
  
  /**
   * Get query value
   */
  public function queryVal ($name)
  {
    if (isset($_GET[$name])) return $_GET[$name];
    return null;
  }
  
  /**
   * Die with response code
   */
  public function end ($code=200)
  {
    $this->json(array('code'=>$code,'message'=>$this->responses[$code]));
  }
  
  /**
   * Send as json
   */
  public function json ($object)
  {
    die (json_encode($object));
  }
}
