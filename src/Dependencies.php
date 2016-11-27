<?php

namespace OhLabs\WPKit;

class Dependencies
{
  use \OhLabs\WPKit\Traits\Singleton;
  use \OhLabs\WPKit\Traits\Debug;
  use \OhLabs\WPKit\Traits\PluginActions;

  /** @var string The class name of the main plugin that requires dependencies */
  protected $plugin;
  /** @var array  Plugin paths to look for with their download locations */
  protected $dependencies = array();
  /** @var string Admin post action used for plugin action link */
  protected $adminPostAction = 'wpkit-install-dependencies';
  /** @var array  Cache missing dependencies */
  protected $missing;

  /**
   * The Constructor
   */
  protected function __construct ()
  {
    $this->usePluginActions();
  }

  /**
   * Insert a plugin action link if there are unmet dependencies
   * @private
   */
  public function pluginActionLinks ($links)
  {
    if (!$this->areMet()) {
      $links[] = $this->renderPluginActionLink('Setup Dependencies');
    }
    return $links;
  }

  /**
   * Install and activate missing dependencies
   * @private
   */
  public function pluginActionCallback ()
  {
    if (!current_user_can('manage_options')) {
      $this->pluginActionCallbackEnd();
    }
    $missing = $this->getMissing();
    if (empty($missing)) {
      $this->pluginActionCallbackEnd();
    }
    @include_once (ABSPATH . 'wp-admin/includes/plugin.php');
    if (!function_exists('activate_plugin')) {
      $this->pluginActionCallbackEnd();
    }
    foreach ($missing as $path => $src) {
      if (!@file_exists(WP_PLUGIN_DIR . '/' . $path)) {
        $file = tempnam(sys_get_temp_dir(),'wpkitdep-');
        file_put_contents($file,fopen($src,'r'));
        $zip = new \ZipArchive;
        $res = $zip->open($file);
        if ($res == TRUE) {
          $zip->extractTo(WP_PLUGIN_DIR);
          $zip->close();
          @unlink($file);
        } else {
          $this->log("Plugin \"$path\" could't be installed.");
        }
      }
      activate_plugin ($path);
    }
    $this->pluginActionCallbackEnd();
  }

  /**
   * Get missing dependencies
   */
  public function getMissing ()
  {
    if (isset($this->missing)) {
      return $this->missing;
    }
    $this->missing = array();
    $registered = sizeof($this->dependencies);
    $active = 0;
    if (empty($registered)) {
      return $this->missing;
    }
    include_once (ABSPATH . 'wp-admin/includes/plugin.php');
    if (function_exists('is_plugin_active')) {
      foreach ($this->dependencies as $path => $src) {
        if (is_plugin_active($path)) {
          $active++;
        } else {
          $this->missing[$path] = $src;
        }
      }
    }
    return $this->missing;
  }

  /**
   * Check if all dependencies are active
   */
  public function areMet ()
  {
    $missing = $this->getMissing();
    return empty($missing);
  }
}
