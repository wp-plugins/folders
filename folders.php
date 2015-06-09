<?php
/**
 * Plugin Name: Folders
 * Description: Arrange media, pages, custom post types and posts into folders
 * Version: 1.2.6
 * Author: Steve North, Aaron Taylor (6-2 Design)
 * Author URI: http://62design.co.uk/wordpress-plugins/folders/
 */

$GLOBALS['globOptions'] = get_option('folders_settings');
$GLOBALS['folder_types'] = get_types_to_show();

function get_types_to_show() {
  global $globOptions;
  $types = array();

  if (empty($globOptions)) {
    return false;
  }

  foreach($globOptions as $key => $value) {
    if ($value == 1) {
      $types[] = str_replace('folders4', '', $key);
    }
  }
  return $types;
}

function searchForId($id, $menu) {
  foreach ($menu as $key => $val) {
    $stripVal = explode('=', $val[2]);
    $stripVal = $stripVal[1];
    if ($stripVal === $id) {
      return $key;
    }
  }
}

function folders_admin_notice(){
  $screen = get_current_screen();
  $getPage = $screen->parent_file;
  if($getPage == 'folders-settings') {
    echo '<div class="update-nag"><p><strong>Notice: </strong>Your folders will <strong>NOT</strong> show in the admin panel until pages, media, posts or post types are added to a folder.</p></div>';
  }
}

add_action('admin_notices', 'folders_admin_notice');

// Include Custom Post Types
include('includes/types.php');

// Include Options Page
include('includes/options.php');
