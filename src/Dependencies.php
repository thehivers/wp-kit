<?php

namespace OhLabs\WPKit;

class Dependencies
{
  use \OhLabs\WPKit\Traits\Singleton;
  use \OhLabs\WPKit\Traits\Debug;

  /** @var string The class name of the main plugin that requires dependencies */
  protected $plugin;
  /** @var array  Plugin paths to look for with their download locations */
  protected $dependencies = array();
  /** @var array  Cache missing dependencies */
  protected $missing;

  /**
   * The Constructor
   */
  protected function __construct ()
  {
    add_action (
      'admin_post_wpkit-setup-missing-dependencies',
      array($this,'_setup_missing_dependencies')
    );
  }

  /**
   * Handle admin post request
   * @private
   */
  public function _setup_missing_dependencies ()
  {
    $this->setupMissingDependencies();
    die(wp_redirect(admin_url('plugins.php')));
  }

  /**
   * Insert a plugin action link if there are unmet dependencies
   * @private
   */
  public function issueAdminNotice ()
  {
    add_action ('admin_notices',array($this,'renderAdminNotice'));
  }

  /**
   * Render admin notice
   */
  public function renderAdminNotice ()
  {
    echo '<div class="notice notice-error">';
    echo '<p>System setup could not complete, please click <a href="';
    echo admin_url('admin-post.php?action=wpkit-setup-missing-dependencies');
    echo '">here</a> to install the missing dependencies.</p>';
    echo '</div>';
  }

  /**
   * Install and activate missing dependencies
   * @private
   */
  public function setupMissingDependencies ()
  {
    if (!current_user_can('manage_options')) {
      return false;
    }
    $missing = $this->getMissingDependencies();
    if (empty($missing)) {
      return false;
    }
    @include_once (ABSPATH . 'wp-admin/includes/plugin.php');
    if (!function_exists('activate_plugin')) {
      return false;
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
    return true;
  }

  /**
   * Get missing dependencies
   */
  public function getMissingDependencies ()
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
    $missing = $this->getMissingDependencies();
    return empty($missing);
  }
}
