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
    return $this;
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
    return $this;
  }

  /**
   * Register plugin stylesheet alias
   */
  public function regPluginStyle ($id,$path,$media=null)
  {
    $this->regStyle($id,$path,$media);
    return $this;
  }

  /**
   * Register theme stylesheet
   */
  public function regThemeStyle ($id,$path,$media=null)
  {
    wp_register_style($id,
    $this->getAssetPath($path,true),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    is_string($media) && !empty($media) ? $media : 'all');
    return $this;
  }

  /**
   * Register chils theme stylesheet
   */
  public function regChildThemeStyle ($id,$path,$media=null)
  {
    wp_register_style($id,
    $this->getAssetPath($path,true,true),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    is_string($media) && !empty($media) ? $media : 'all');
    return $this;
  }

  /**
   * Enqueue script
   */
  public function useScript ($id)
  {
    wp_enqueue_script($id);
    return $this;
  }

  /**
   * Register plugin javascript
   */
  public function regScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    $this->getAssetPath($path),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    $footer);
    return $this;
  }

  /**
   * Register plugin script alias
   */
  public function regPluginScript ($id,$path,$footer=true)
  {
    $this->regScript($id,$path,$footer);
    return $this;
  }

  /**
   * Register theme javascript
   */
  public function regThemeScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    $this->getAssetPath($path,true),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    $footer);
    return $this;
  }

  /**
   * Register child theme javascript
   */
  public function regChildThemeScript ($id,$path,$footer=true)
  {
    wp_register_script($id,
    $this->getAssetPath($path,true,true),
    array_slice(func_get_args(),3),
    $this->plugin::VERSION,
    $footer);
    return $this;
  }

  /**
   * Localize script
   */
  public function inject ($id,$name,$data)
  {
    wp_localize_script($id,$name,$data);
    return $this;
  }
}
