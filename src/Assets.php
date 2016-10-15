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
   * Get asset path
   */
  public function getAssetPath ($path,$theme=false,$child=false)
  {
    if (preg_match('/(^\/\/)|(^https?\:\/\/)/',$path))
    return  $path;
    return !$theme
    ? $this->plugin::url("/{$this->base}/{$path}")
    : ($child ? get_template_directory_uri() : get_stylesheet_directory_uri())
    . "/{$this->base}/{$path}";
  }

  /**
   * Enqueue style
   */
  public function useStyle ($id)
  {
    wp_enqueue_style($id);
  }

  /**
   * Register plugin stylesheet
   */
  public function regStyle ($id,$path,$media=null)
  {
    wp_register_style($id,
    $this->getAssetPath($path),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    is_string($media) && !empty($media) ? $media : 'all');
  }

  /**
   * Register plugin stylesheet alias
   */
  public function regPluginStyle ($id,$path,$media=null)
  {
    $this->regStyle($id,$path,$media);
  }

  /**
   * Register theme stylesheet
   */
  public function regThemeStyle ($id,$path,$media=null)
  {
    wp_register_style($id,
    $this->regAssetPath($path,true),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    is_string($media) && !empty($media) ? $media : 'all');
  }

  /**
   * Register chils theme stylesheet
   */
  public function regChildThemeStyle ($id,$path,$media=null)
  {
    wp_register_style($id,
    $this->regAssetPath($path,true,true),
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
   * Register plugin javascript
   */
  public function regScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    $this->regAssetPath($path),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    $footer);
  }

  /**
   * Register plugin script alias
   */
  public function regPluginScript ($id,$path,$footer=true)
  {
    $this->regScript($id,$path,$footer);
  }

  /**
   * Register theme javascript
   */
  public function regThemeScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    $this->regAssetPath($path,true),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    $footer);
  }

  /**
   * Register child theme javascript
   */
  public function regChildThemeScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    $this->regAssetPath($path,true,true),
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
