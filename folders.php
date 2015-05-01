<?php
/**
 * Plugin Name: Folders
 * Description: Arrange pages and/or posts into folders
 * Version: 1.0.0
 * Author: Steve North (6-2 Design)
 * Author URI: http://62design.co.uk/wordpress-plugins/folders/
 */


/************************
********** PAGES ********
*************************/
$globOptions = get_option('folders_settings');
if (!empty($globOptions['folders4pages']) && $globOptions['folders4pages'] == 1) {
  // Add the Custom Taxonomy
  function add_custom_folder_taxonomy() {
    register_taxonomy('folder', 'page', array(
      'hierarchical' => true,
      'labels' => array(
        'name' => _x( 'Folders', 'taxonomy general name' ),
        'singular_name' => _x( 'Folder', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Folders' ),
        'all_items' => __( 'All Folders' ),
        'parent_item' => __( 'Parent Folder' ),
        'parent_item_colon' => __( 'Parent Folder:' ),
        'edit_item' => __( 'Edit Folder' ),
        'update_item' => __( 'Update Folder' ),
        'add_new_item' => __( 'Add New Folder' ),
        'new_item_name' => __( 'New Folder Name' ),
        'menu_name' => __( 'Folders' ),
      ),
      'rewrite' => array(
        'slug' => '',
        'with_front' => false,
        'hierarchical' => false
      ),
    ));
  }
  add_action( 'init', 'add_custom_folder_taxonomy', 0 );

  // Add Taxonomy Filters
  function folders_add_taxonomy_filters() {
    global $typenow;
    $taxonomies = array('folder');
    if( $typenow == 'page' ){
      foreach ($taxonomies as $tax_slug) {
        $tax_obj = get_taxonomy($tax_slug);
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms($tax_slug);
        if(count($terms) > 0) {
          echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
          echo "<option value=''>Show All $tax_name</option>";
          foreach ($terms as $term) {
            echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
          }
          echo "</select>";
        }
      }
    }
  }
  add_action( 'restrict_manage_posts', 'folders_add_taxonomy_filters' );

  // Add Folders into Admin Menu
  add_action('admin_menu', 'folders_pages_in_admin_menu');
  function folders_pages_in_admin_menu(){
    $taxonomies = array('folder');
    add_menu_page( 'Page Folders', 'Page Folders', 'publish_pages', 'edit.php?post_type=page&folder', false, plugin_dir_url(__FILE__).'/assets/img/folder-icon-pages.png', 21 );
    add_submenu_page( 'edit.php?post_type=page&folder', 'Add/Edit Folders', 'Add/Edit Folders', 'publish_pages', 'edit-tags.php?taxonomy=folder&post_type=page', false );
    foreach ($taxonomies as $key => $tax_slug) {
      $tax_obj = get_taxonomy($tax_slug);
      $tax_name = $tax_obj->labels->name;
      $terms = get_terms($tax_slug);
      if(count($terms) > 0) {
        foreach ($terms as $term) {
          add_submenu_page( 'edit.php?post_type=page&folder', $term->name, $term->name, 'publish_pages', 'edit.php?post_type=page&folder='.$term->slug, false );
        }
      }
    }
    remove_submenu_page( 'edit.php?post_type=page&folder', 'edit.php?post_type=page&folder' );
    remove_submenu_page( 'edit.php?post_type=page', 'edit-tags.php?taxonomy=folder&amp;post_type=page' );
  }

  function add_folder_column( $columns ) {
    $myCustomColumns = array(
      'folder' => __( 'Folder', 'Folder' )
    );
    $columns = array_merge( $columns, $myCustomColumns );
    return $columns;
  }
  add_filter( 'manage_pages_columns', 'add_folder_column' );

  function add_folder_column_content( $column_name, $post_id ) {
    if ( $column_name == 'folder' ) {
      $ter = wp_get_post_terms($post_id, 'folder' );
      $count = count($ter);
      foreach ($ter as $key => $term) {
        if ($count === $key + 1) {
        echo $term->name;
        }
        else {
          echo $term->name.', ';
        }
      }
    }
  }
add_action( 'manage_pages_custom_column', 'add_folder_column_content', 10, 2 );
}

/************************
********** POSTS ********
*************************/

if (!empty($globOptions['folders4posts']) && $globOptions['folders4posts'] == 1) {
  // Add the Custom Taxonomy
  function add_custom_posts_folder_taxonomy() {
    register_taxonomy('post_folder', 'post', array(
      'hierarchical' => true,
      'labels' => array(
        'name' => _x( 'Folders', 'taxonomy general name' ),
        'singular_name' => _x( 'Folder', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Folders' ),
        'all_items' => __( 'All Folders' ),
        'parent_item' => __( 'Parent Folder' ),
        'parent_item_colon' => __( 'Parent Folder:' ),
        'edit_item' => __( 'Edit Folder' ),
        'update_item' => __( 'Update Folder' ),
        'add_new_item' => __( 'Add New Folder' ),
        'new_item_name' => __( 'New Folder Name' ),
        'menu_name' => __( 'Folders' ),
      ),
      'rewrite' => array(
        'slug' => '',
        'with_front' => false,
        'hierarchical' => false
      ),
    ));
  }
  add_action( 'init', 'add_custom_posts_folder_taxonomy', 0 );

  // Add Taxonomy Filters
  function folders_add_post_taxonomy_filters() {
    global $typenow;
    $taxonomies = array('post_folder');
    if( $typenow == 'post' ){
      foreach ($taxonomies as $tax_slug) {
        $tax_obj = get_taxonomy($tax_slug);
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms($tax_slug);
        if(count($terms) > 0) {
          echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
          echo "<option value=''>Show All $tax_name</option>";
          foreach ($terms as $term) {
            echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
          }
          echo "</select>";
        }
      }
    }
  }
  add_action( 'restrict_manage_posts', 'folders_add_post_taxonomy_filters' );

  // Add Folders into Admin Menu
  add_action('admin_menu', 'folders_posts_in_admin_menu');
  function folders_posts_in_admin_menu(){
    $taxonomies = array('post_folder');
    add_menu_page( 'Post Folders', 'Post Folders', 'publish_pages', 'edit.php?post_type=post&post_folder', false, plugin_dir_url(__FILE__).'/assets/img/folder-icon-posts.png', 6 );
    add_submenu_page( 'edit.php?post_type=post&post_folder', 'Add/Edit Folders', 'Add/Edit Folders', 'publish_pages', 'edit-tags.php?taxonomy=post_folder&post_type=page', false );
    foreach ($taxonomies as $key => $tax_slug) {
      $tax_obj = get_taxonomy($tax_slug);
      $tax_name = $tax_obj->labels->name;
      $terms = get_terms($tax_slug);
      if(count($terms) > 0) {
        foreach ($terms as $term) {
          add_submenu_page( 'edit.php?post_type=post&post_folder', $term->name, $term->name, 'publish_pages', 'edit.php?post_type=post&post_folder='.$term->slug, false );
        }
      }
    }
    remove_submenu_page( 'edit.php?post_type=post&post_folder', 'edit.php?post_type=post&post_folder' );
    remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_folder' );
  }

  function add_folder_posts_column( $columns ) {
    $myCustomColumns = array(
      'folder' => __( 'Folder', 'Folder' )
    );
    $columns = array_merge( $columns, $myCustomColumns );
    return $columns;
  }
  add_filter( 'manage_posts_columns', 'add_folder_posts_column' );

  function add_folder_posts_column_content( $column_name, $post_id ) {
    if ( $column_name == 'folder' ) {
      $ter = wp_get_post_terms($post_id, 'post_folder' );
      $count = count($ter);
      foreach ($ter as $key => $term) {
        if ($count === $key + 1) {
        echo $term->name;
        }
        else {
          echo $term->name.', ';
        }
      }
    }
  }
  add_action( 'manage_posts_custom_column', 'add_folder_posts_column_content', 10, 2 );
}

/*************************/
/********* OPTIONS *******/
/*************************/

add_action('admin_menu', 'folders_admin_page');
function folders_admin_page(){
    add_menu_page('Folder Settings', 'Folder Settings', 'administrator', 'folders-settings', 'folders_admin_page_callback');
}

add_action('admin_init', 'folders_register_settings');
function folders_register_settings(){
  register_setting('folders_settings', 'folders_settings', 'folders_settings_validate');
}

function folders_settings_validate($args){
  return $args;
}

add_action('admin_notices', 'folders_admin_notices');

function folders_admin_notices(){
   settings_errors();
}

function folders_admin_page_callback(){ ?>
<div class="wrap">
  <h2>Folder Settings</h2>
  <form action="options.php" method="post"><?php
    settings_fields( 'folders_settings' );
    do_settings_sections( __FILE__ );

    $options = get_option( 'folders_settings' ); ?>
    <table class="form-table">
        <tr>
          <th scope="row">Use Folders with Pages</th>
          <td>
            <fieldset>
                <label>
                    <input name="folders_settings[folders4pages]" type="checkbox" id="folders4pages" value="1" <?php if (!empty($options['folders4pages']) && $options['folders4pages'] == 1) {echo 'checked';} ?>/>
                    <br />
                </label>
            </fieldset>
          </td>
        </tr>
        <tr>
          <th scope="row">Use Folders with Posts</th>
          <td>
            <fieldset>
                <label>
                  <input name="folders_settings[folders4posts]" type="checkbox" id="folders4posts" value="1" <?php if (!empty($options['folders4posts']) && $options['folders4posts'] == 1) {echo 'checked';} ?>/>
                  <br />
                </label>
            </fieldset>
          </td>
        </tr>
    </table>
    <?php submit_button(); ?>
  </form>
  <div style="display:block;">
    <h3>Donate</h3>
    <strong>Folders is a plugin developed by <a target="_blank" href="http://62design.co.uk">6-2 Design</a>.</strong>
    <p>Please consider donating a small amount to help us keep Folders in development.</p>
    <a href="http://62design.co.uk/wordpress-plugins/folders/" target="_blank">Click here to donate</a>
    </div>
</div>
<?php }
