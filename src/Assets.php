<?php

namespace OhLabs\WPKit;

class Assets
{
  use \OhLabs\WPKit\Traits\Singleton;
  
  protected $plugin;
  protected $base = 'public';
  
  /**
   * Front
   */
  public static function doFront ()
  {
    static::instance()->common();
    static::instance()->front();
  }
  
  /**
   * Admin
   */
  public static function doAdmin ()
  {
    static::instance()->common();
    static::instance()->admin();
  }
  
  /** Executed in front end */
  public function front  () {}
  /** Executed in admin end */
  public function admin  () {}
  /** Executed in both ends */
  public function common () {}
  
  /**
   * Enqueue style
   */
  public function useStyle ($id)
  {
    wp_enqueue_style($id);
  }
  
  /**
   * Register stylesheet
   */
  public function regStyle ($id,$path,$media=null)
  {
    wp_register_style($id,
    !preg_match('/(^\/\/)|(^https?\:\/\/)/',$path) ?
    $this->plugin::url("{$this->base}/{$path}") : $path,
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    is_string($media) && !empty($media) ? $media : 'all');
  }
  
  /**
   * Register theme stylesheet
   */
  public function regThemeStyle ($id,$path,$media=null)
  {
    wp_register_style($id,
    !preg_match('/(^\/\/)|(^https?\:\/\/)/',$path) ?
    (get_template_directory_uri() . "/{$this->base}/{$path}") : $path,
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    is_string($media) && !empty($media) ? $media : 'all');
  }
  
  /**
   * Enqueue script
   */
  public function useScript ($id)
  {
    wp_enqueue_script($id);
  }
  
  /**
   * Register javascript
   */
  public function regScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    !preg_match('/(^\/\/)|(^https?\:\/\/)/',$path) ?
    $this->plugin::url("{$this->base}/{$path}") : $path,
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    $footer);
  }
  
  /**
   * Register javascript
   */
  public function regThemeScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    !preg_match('/(^\/\/)|(^https?\:\/\/)/',$path) ?
    (get_template_directory_uri() . "/{$this->base}/{$path}") : $path,
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    $footer);
  }
  
  /**
   * Localize script
   */
  public function inject ($id,$name,$data)
  {
    wp_localize_script($id,$name,$data);
  }
}