<?php

namespace OhLabs\WPKit;

class Dependencies
{
  use \OhLabs\WPKit\Traits\Singleton;
  use \OhLabs\WPKit\Traits\Debug;

  /** @var string The class name of the main plugin that requires dependencies */
  protected $plugin;
  /** @var array  Plugin paths to look for with their download locations */
  protected $plugins = array();

  /**
   * The Constructor
   */
  protected function __construct ()
  {
    add_filter (
      'plugin_action_links_' . $this->plugin::NAME . '/' . $this->plugin::ENTRY,
      array($this,'_install_deps_plugin_action')
    );
    add_action (
      'admin_post_wpkit-install-dependencies',
      array($this,'_install_missing_dependencies')
    );
  }

  /**
   * Insert a plugin action link if there are unmet dependencies
   * @private
   */
  public function _install_deps_plugin_action ($links)
  {
    $met = $this->areMet();
    if (!$met) {
      $action  = '<a href="';
      $action .= admin_url('admin-post.php?action=wpkit-install-dependencies');
      $action .= '" title="';
      $action .= 'Install missing dependencies and activate deactivated ones.';
      $action .= '">Setup Dependencies</a>';
      $links[] = $action;
    }
    return $links;
  }

  /**
   * Install and activate missing dependencies
   * @private
   */
  public function _install_missing_dependencies ()
  {
    if (!current_user_can('manage_options')) {
      die(wp_redirect(admin_url('plugins.php')));
    }
    $missing = $this->getMissing();
    if (empty($missing)) {
      die(wp_redirect(admin_url('plugins.php')));
    }
    @include_once (ABSPATH . 'wp-admin/includes/plugin.php');
    if (!function_exists('activate_plugin')) {
      die(wp_redirect(admin_url('plugins.php')));
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
    die (wp_redirect(admin_url('plugins.php')));
  }

  /**
   * Get missing dependencies
   */
  public function getMissing ()
  {
    $registered = sizeof($this->plugins);
    $missing = array();
    $active = 0;
    if (empty($registered)) return $missing;
    include_once (ABSPATH . 'wp-admin/includes/plugin.php');
    if (function_exists('is_plugin_active')) {
      foreach ($this->plugins as $path => $src) {
        if (is_plugin_active($path)) {
          $active++;
        } else {
          $missing[$path] = $src;
        }
      }
    }
    return $missing;
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
