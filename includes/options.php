<?php
/*************************/
/********* OPTIONS *******/
/*************************/

function folders_admin_page(){
    add_menu_page('Folder Settings', 'Folder Settings', 'administrator', 'folders-settings', 'folders_admin_page_callback');
}
add_action('admin_menu', 'folders_admin_page');

function folders_register_settings(){
  register_setting('folders_settings', 'folders_settings', 'folders_settings_validate');
}
add_action('admin_init', 'folders_register_settings');

function folders_settings_validate($args){
  return $args;
}

function folders_admin_notices(){
   settings_errors();
}
add_action('admin_notices', 'folders_admin_notices');

function folders_admin_page_callback(){
  global $globOptions;
?>
<div class="wrap">
  <h2>Folder Settings</h2>
  <form action="options.php" method="post"><?php
    settings_fields( 'folders_settings' );
    do_settings_sections( __FILE__ );

    $options = $globOptions; ?>
    <table class="form-table">
        <?php
        $args = array(
          'public' => true
        );
        $postTypes = get_post_types($args);
        foreach ($postTypes as $type) { ?>
        <tr>
          <th scope="row"><label for="folders4<?php echo $type; ?>">Use Folders with <?php echo ucfirst($type); ?></label></th>
          <td>
            <fieldset>
              <input name="folders_settings[folders4<?php echo $type; ?>]" type="checkbox" id="folders4<?php echo $type; ?>" value="1" <?php if (!empty($options['folders4'.$type]) && $options['folders4'.$type] == 1) {echo 'checked';} ?>/>
              <br />
            </fieldset>
          </td>
        </tr>
        <?php } ?>
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
