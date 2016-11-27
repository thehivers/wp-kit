<?php

namespace OhLabs\WPKit\Traits;

trait PluginActions
{
  /**
   * Plugin action hooks
   */
  public function usePluginActions ()
  {
    add_filter (
      'plugin_action_links_' . $this->plugin::NAME . '/' . $this->plugin::ENTRY,
      array($this,'pluginActionLinks')
    );
    add_action (
      'admin_post_' . $this->adminPostAction,
      array($this,'pluginActionCallback')
    );
  }

  /**
   * Render plugin action links
   */
  public function renderPluginActionLink ($label,$subaction='')
  {
    $action  = "<a href=\"";
    $action .= admin_url("admin-post.php?action={$this->adminPostAction}&subaction={$subaction}");
    $action .= "\">{$label}</a>";
    return $action;
  }

  /**
   * Plugin action callback end
   */
  public function pluginActionCallbackEnd ()
  {
    die(wp_redirect(admin_url('plugins.php')));
  }
}
