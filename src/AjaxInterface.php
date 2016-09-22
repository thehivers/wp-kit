<?php

namespace OhLabs\WPKit;

class AjaxInterface
{
  use \OhLabs\WPKit\Traits\Singleton;
  
  /** @const string Default error */
  const DEFAULT_ERROR = 'Oops! An error has occured while getting your request. Please try again.';
  /** @const string Default success */
  const DEFAULT_SUCCESS = 'All good.';
  /** @var array Response messages (code => message) */
  protected $responses = array (0=>'All good.');
  
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
   * Get file value
   */
  public function fileVal ($name)
  {
    if (isset($_FILES[$name])) return $_FILES[$name];
    return null;
  }
  
  /**
   * Die with response code
   */
  public function end ($code=0)
  {
    $res = array('error'=>false,'code'=>$code);
    $res['message'] = isset($this->responses[$code])
    ? $this->responses[$code]
    : $code > 0
    ? static::DEFAULT_ERROR
    : static::DEFAULT_SUCCESS;
    $this->json(array('code'=>$code));
  }
  
  /**
   * Die with error code
   */
  public function error ($code=1,$message=null)
  {
    $res = array('error'=>true,'code'=>$code);
    $res['message'] = is_string($message)
    ? $message
    : static::DEFAULT_ERROR;
    $this->json($res);
  }
  
  /**
   * Send as json
   */
  public function json ($object)
  {
    die (json_encode($object));
  }
}
