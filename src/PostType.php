<?php

namespace OhLabs\WPKit;

class PostType
{
  use \OhLabs\WPKit\Traits\Singleton;
  
  protected $id;
  protected $name;
  protected $plural;
  
  protected $labels = array();
  protected $arguments = array();
  
  /**
   * The Constructor
   */
  protected function __construct ()
  {
    if (!isset($this->name) || !is_string($this->name) || empty($this->name))
    throw new \Exception ('PostType: Name not set.');
    
    $this->id = strtolower(str_replace(' ','_',$this->name));
    $this->plural = $this->name[strlen($this->name)-1] == 'y'
    ? (substr($this->name,0,-1) . 'ies')
    : $this->name . 's';
    
    $this->labels = array_merge(array(
      'name'                  => _x($this->plural,'post type general name'),
      'singular_name'         => _x($this->name,'post type singular name'),
      'add_new'               => _x('Add New',strtolower($this->name)),
      'add_new_item'          => __('Add New '.$this->name),
      'edit_item'             => __('Edit '.$this->name),
      'new_item'              => __('New '.$this->name),
      'all_items'             => __('All '.$this->plural),
      'view_item'             => __('View '.$this->name),
      'search_items'          => __('Search '.$this->plural),
      'not_found'             => __('No '.strtolower($this->plural).' found'),
      'not_found_in_trash'    => __('No '.strtolower($this->plural).' found in Trash'), 
      'parent_item_colon'     => '',
      'menu_name'             => $this->plural
    ),$this->labels);
    
    $this->arguments = array_merge(array(
      'label'             => $this->plural,
      'labels'            => $this->labels,
      'public'            => true,
      'show_ui'           => true,
      'supports'          => array('title','editor'),
      'show_in_nav_menus' => true,
      '_builtin'          => false
    ),$this->arguments);
    
    add_action ('init',array($this,'_register_post_type'));
  }
  
  /**
   * Register post type with wordpress
   * @private
   */
  public function _register_post_type ()
  {
    register_post_type($this->id,$this->arguments);
  }
}
