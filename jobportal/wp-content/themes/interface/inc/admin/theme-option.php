<?php
/**
 * Interface Theme Options
 *
 * Contains all the function related to theme options.
 *
 * @package Theme Horse
 * @subpackage Interface
 * @since Interface 1.0
 */

/****************************************************************************************/

add_action( 'admin_enqueue_scripts', 'interface_jquery_javascript_file_cookie' );
/**
 * Register jquery cookie javascript file.
 *
 * jquery cookie used for remembering admin tabs, and potential future features... so let's register it early
 *
 * @uses wp_register_script
 */
function interface_jquery_javascript_file_cookie() {
   wp_register_script( 'jquery-cookie', INTERFACE_ADMIN_JS_URL . '/jquery.cookie.min.js', array( 'jquery' ) );
   wp_enqueue_style('thickbox');

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}

/****************************************************************************************/

add_action( 'admin_print_scripts-appearance_page_theme_options', 'interface_admin_scripts' );
/**
 * Enqueuing some scripts.
 *
 * @uses wp_enqueue_script to register javascripts.
 * @uses wp_enqueue_script to add javascripts to WordPress generated pages.
 */
function interface_admin_scripts() {
   wp_enqueue_script( 'interface_admin', INTERFACE_ADMIN_JS_URL . '/admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-cookie', 'jquery-ui-sortable', 'jquery-ui-draggable' ) );
   wp_enqueue_script( 'interface_toggle_effect', INTERFACE_ADMIN_JS_URL . '/toggle-effect.js' );
   wp_enqueue_script( 'interface_image_upload', INTERFACE_ADMIN_JS_URL . '/add-image-script.js', array( 'jquery','media-upload', 'thickbox' ) );
}

/****************************************************************************************/

add_action( 'admin_print_styles-appearance_page_theme_options', 'interface_admin_styles' );
/**
 * Enqueuing some styles.
 *
 * @uses wp_enqueue_style to register stylesheets.
 * @uses wp_enqueue_style to add styles.
 */
function interface_admin_styles() {
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'interface_admin_style', INTERFACE_ADMIN_CSS_URL. '/admin.css' );
}

/****************************************************************************************/

add_action( 'admin_print_styles-appearance_page_theme_options', 'interface_social_script', 100);
/**
 * Facebook, twitter script hooked at head
 * 
 * @useage for Facebook, Twitter and Print Script 
 * @Use add_action to display the Script on header
 */
function interface_social_script() { ?>
<!-- Facebook script -->


<div id="fb-root"></div>
<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=284802028306078";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script> 

<!-- Twitter script --> 
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script> 

<!-- Print Script --> 
<script src="http://cdn.printfriendly.com/printfriendly.js" type="text/javascript"></script>
<?php     
}

/****************************************************************************************/

add_action( 'admin_menu', 'interface_options_menu' );
/**
 * Create sub-menu page.
 *
 * @uses add_theme_page to add sub-menu under the Appearance top level menu.
 */
function interface_options_menu() {
    
	add_theme_page( 
		__( 'Theme Options', 'interface' ),           // Name of the page
		__( 'Theme Options', 'interface' ),           // Label in menu (Inside apperance)
		'edit_theme_options',                         // Capability 
		'theme_options',                              // Menu slug, which is used to define uniquely the page
		'interface_theme_options_add_theme_page'      // Function used to rendrs the options page
	);

}

/****************************************************************************************/

add_action( 'admin_init', 'interface_register_settings' );
	/**
		* Register options and function call back of validation
		*
		* this three options interface_theme_options', 'interface_theme_options', 'interface_theme_options_validate'
		* first parameter interface_theme_options  =>    To set all field eg:- social link, design options etc.
		* second parameter interface_theme_options =>	 Option value to sanitize and save. array values etc. can be called global 
		* third parameter interface_theme_options  => 	 Call back function
		* @uses register_setting
	*/
function interface_register_settings() {
   register_setting( 'interface_theme_options', 'interface_theme_options', 'interface_theme_options_validate' );
 
}

/****************************************************************************************/
/**
 * Render the options page
 */
function interface_theme_options_add_theme_page() {
?>
<div class="them_option_block clearfix">
  <div class="theme_option_title">
    <h2>
      <?php _e( 'Theme Options by', 'interface' ); ?>
    </h2>
  </div>
  <div class="theme_option_link"><a href="<?php echo esc_url( __( 'http://themehorse.com/', 'interface' ) ); ?>" title="<?php esc_attr_e( 'Theme Horse', 'interface' ); ?>" target="_blank"><img src="<?php echo INTERFACE_ADMIN_IMAGES_URL . '/theme-horse.png'; ?>" alt="'<?php _e( 'Theme Horse', 'interface' ); ?>" /></a> </div>
</div>
<br/>
<br/>
<br/>
<div class="donate-info"> <strong>
  <?php _e( 'Hey! buy us a beer and we shall come with new features and update.', 'interface' ); ?>
  </strong><br/>
  <a title="<?php esc_attr_e( 'Donate', 'interface' ); ?>" href="<?php echo esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=BRLCCUGP2ACYN' ); ?>" target="_blank" class="donate">
  <?php _e( 'Donate', 'interface' ); ?>
  </a> <a title="<?php esc_attr_e( 'Review Interface', 'interface' ); ?>" href="<?php echo esc_url( 'http://wordpress.org/support/view/theme-reviews/interface' ); ?>" target="_blank" class="review">
  <?php _e( 'Rate Interface', 'interface' ); ?>
  </a>
   <a href="<?php echo esc_url( 'http://themehorse.com/theme-instruction/interface/' ); ?>" title="<?php esc_attr_e( 'Interface Theme Instructions', 'interface' ); ?>" target="_blank" class="instruction">
    <?php _e( 'Theme Instructions', 'interface' ); ?>
  </a>
  <a href="<?php echo esc_url( 'http://themehorse.com/support-forum/' ); ?>" title="<?php esc_attr_e( 'Support Forum', 'interface' ); ?>" target="_blank" class="support">
    <?php _e( 'Support Forum', 'interface' ); ?>
  </a>
  <a href="<?php echo esc_url( 'http://themehorse.com/preview/interface/' ); ?>" title="<?php esc_attr_e( 'Interface Demo', 'interface' ); ?>" target="_blank" class="demo">
    <?php _e( 'View Demo', 'interface' ); ?>
  </a>
  <a href="<?php echo esc_url( 'http://themehorse.com/themes/interface-pro/' ); ?>" title="<?php esc_attr_e( 'Upgrade to Pro', 'interface' ); ?>" target="_blank" class="upgrade">
    <?php _e( 'Upgrade to Pro', 'interface' ); ?>
  </a>
  <div id="social-share">
    <div class="fb-like" data-href="https://www.facebook.com/themehorse" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true"></div>
    <div class="tw-follow" ><a href="<?php echo esc_url( 'http://twitter.com/Theme_Horse' ); ?>" class="twitter-follow-button" data-button="grey" data-text-color="#FFFFFF" data-link-color="#00AEFF" data-width="150px" data-show-screen-name="true" data-show-count="false"></a></div>
  </div>
</div>
<div id="themehorse" class="wrap">
  <form method="post" action="options.php">
    <?php
			/**
			* should match with the register_settings first parameter of line no 117
			*/
				settings_fields( 'interface_theme_options' ); 
				global $interface_theme_default;
				$options = $interface_theme_default;             
			?>
    <?php if( isset( $_GET [ 'settings-updated' ] ) && 'true' == $_GET[ 'settings-updated' ] ): ?>
    <div class="updated" id="message">
      <p><strong>
        <?php _e( 'Settings saved.', 'interface' );?>
        </strong></p>
    </div>
    <?php endif; ?>
    <div id="interface_tabs">
      <ul id="main-navigation" class="tab-navigation">
        <li><a href="#responsivelayout">
          <?php _e( 'Layout Options', 'interface' );?>
          </a></li>
        <li><a href="#designoptions">
          <?php _e( 'Design Options', 'interface' );?>
          </a></li>
        <li><a href="#advancedoptions">
          <?php _e( 'Advance Options', 'interface' );?>
          </a></li>
        <li><a href="#featuredpostslider">
          <?php _e( 'Featured Post/Page Slider', 'interface' );?>
          </a></li>
        <li><a href="#sociallink">
          <?php _e( 'Contact / Social Links', 'interface' );?>
          </a></li>
      </ul>
      <!-- .tab-navigation #main-navigation --> 
      <!-- Option for Design Options -->
      <div id="responsivelayout">
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Site Layout', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"><label>
                      <?php _e( 'Site Layout', 'interface' ); ?>
                    </label>
                    <p><small>
                      <?php _e( 'This change is reflected in whole website', 'interface' ); ?>
                      </small></p>
                  </th>
                  <td><label title="narrow-layout" class="box" style="margin-right: 18px"><img src="<?php echo INTERFACE_ADMIN_IMAGES_URL; ?>/one-column.png" alt="Content-Sidebar" /><br />
                      <input type="radio" name="interface_theme_options[site_layout]" id="narrow-layout" <?php checked($options['site_layout'], 'narrow-layout') ?> value="narrow-layout"  />
                      <?php _e( 'Narrow Layout', 'interface' ); ?>
                    </label>
                    <label title="wide-layout" class="box" style="margin-right: 18px"><img src="<?php echo INTERFACE_ADMIN_IMAGES_URL; ?>/no-sidebar-fullwidth.png" alt="Content-Sidebar" /><br />
                      <input type="radio" name="interface_theme_options[site_layout]" id="wide-layout" <?php checked($options['site_layout'], 'wide-layout') ?> value="wide-layout"  />
                      <?php _e( 'Wide Layout', 'interface' ); ?>
                    </label></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Content Layout', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"><label>
                      <?php _e( 'Layouts', 'interface' ); ?>
                    </label></th>
                  <td><label title="no-sidebar" class="box" style="margin-right: 18px"><img src="<?php echo INTERFACE_ADMIN_IMAGES_URL; ?>/no-sidebar.png" alt="Content-Sidebar" /><br />
                      <input type="radio" name="interface_theme_options[default_layout]" id="no-sidebar" <?php checked($options['default_layout'], 'no-sidebar') ?> value="no-sidebar"  />
                      <?php _e( 'No Sidebar', 'interface' ); ?>
                    </label>
                    <label title="no-sidebar-full-width" class="box" style="margin-right: 18px"><img src="<?php echo INTERFACE_ADMIN_IMAGES_URL; ?>/no-sidebar-fullwidth.png" alt="Content-Sidebar" /><br />
                      <input type="radio" name="interface_theme_options[default_layout]" id="no-sidebar-full-width" <?php checked($options['default_layout'], 'no-sidebar-full-width') ?> value="no-sidebar-full-width"  />
                      <?php _e( 'No Sidebar, Full Width', 'interface' ); ?>
                    </label>
                    <label title="left-Sidebar" class="box" style="margin-right: 18px"><img src="<?php echo INTERFACE_ADMIN_IMAGES_URL; ?>/left-sidebar.png" alt="Content-Sidebar" /><br />
                      <input type="radio" name="interface_theme_options[default_layout]" id="left-sidebar" <?php checked($options['default_layout'], 'left-sidebar') ?> value="left-sidebar"  />
                      <?php _e( 'Left Sidebar', 'interface' ); ?>
                    </label>
                    <label title="right-sidebar" class="box" style="margin-right: 18px"><img src="<?php echo INTERFACE_ADMIN_IMAGES_URL; ?>/right-sidebar.png" alt="Content-Sidebar" /><br />
                      <input type="radio" name="interface_theme_options[default_layout]" id="right-sidebar" <?php checked($options['default_layout'], 'right-sidebar') ?> value="right-sidebar"  />
                      <?php _e( 'Right Sidebar', 'interface' ); ?>
                    </label></td>
                </tr>
                <?php if( "1" == $options[ 'reset_layout' ] ) { $options[ 'reset_layout' ] = "0"; } ?>
                <tr>
                  <th scope="row"><label for="reset_layout">
                      <?php _e( 'Reset Layout', 'interface' ); ?>
                    </label></th>
                  <input type='hidden' value='0' name='interface_theme_options[reset_layout]'>
                  <td><input type="checkbox" id="reset_layout" name="interface_theme_options[reset_layout]" value="1" <?php checked( '1', $options['reset_layout'] ); ?> />
                    <?php _e('Check to reset', 'interface'); ?></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Responsive Layout', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"><label>
                      <?php _e( 'Responsive Layout', 'interface' ); ?>
                    </label></th>
                  <td><label title="on" class="box">
                      <input type="radio" name="interface_theme_options[site_design]" id="on" <?php checked($options['site_design'], 'on') ?> value="on"  />
                      <?php _e( 'ON <span class="description">(Responsive view will be displayed in small devices )</span>', 'interface' ); ?>
                    </label>
                    <label title="off" class="box">
                      <input type="radio" name="interface_theme_options[site_design]" id="off" <?php checked($options['site_design'], 'off') ?> value="off"  />
                      <?php _e( 'OFF   <span class="description">(Full site will display as desktop view)</span>', 'interface' ); ?>
                    </label></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container --> 
      </div>
      <!-- #Responsive Layout -->
      <div id="designoptions">
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Custom Header', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"><label for="header_logo">
                      <?php _e( 'Header Logo', 'interface' ); ?>
                    </label></th>
                  <td><input class="upload" size="65" type="text" id="header_logo" name="interface_theme_options[header_logo]" value="<?php echo esc_url( $options [ 'header_logo' ] ); ?>" />
                    <input class="upload-button button" name="image-add" type="button" value="<?php esc_attr_e( 'Change Header Logo', 'interface' ); ?>" /></td>
                </tr>
                <tr>
                  <th scope="row"><?php _e( 'Preview', 'interface' ); ?></th>
                  <td><?php
										       echo '<img src="'.esc_url( $options[ 'header_logo' ] ).'" alt="'.__( 'Header Logo', 'interface' ).'" />';
										   ?></td>
                </tr>
                <tr>
                  <th scope="row"><label>
                      <?php _e( 'Show', 'interface' ); ?>
                    </label></th>
                  <td><?php // interface_theme_options[header_show] this is defined in register_setting second parameter?>
                    <input type="radio" name="interface_theme_options[header_show]" id="header-logo" <?php checked($options['header_show'], 'header-logo') ?> value="header-logo"  />
                    <?php _e( 'Header Logo Only', 'interface' ); ?>
                    </br>
                    <input type="radio" name="interface_theme_options[header_show]" id="header-text" <?php checked($options['header_show'], 'header-text') ?> value="header-text"  />
                    <?php _e( 'Header Text Only', 'interface' ); ?>
                    </br>
                    <input type="radio" name="interface_theme_options[header_show]" id="header-text" <?php checked($options['header_show'], 'disable-both') ?> value="disable-both"  />
                    <?php _e( 'Disable', 'interface' ); ?>
                    </br></td>
                </tr>
                <tr>
                  <th> <?php _e( 'Need to replace Header Image?', 'interface' ); ?>
                  </th>
                  <td><?php printf( __('<a class="button" href="%s">Click here</a>', 'interface' ), admin_url('themes.php?page=custom-header')); ?></td>
                </tr>
                <tr>
                  <th scope="row"><?php _e( 'Hide Searchform from Header', 'interface' ); ?></th>
                  <input type='hidden' value='0' name='interface_theme_options[hide_header_searchform]'>
                  <td><input type="checkbox" id="headerlogo" name="interface_theme_options[hide_header_searchform]" value="1" <?php checked( '1', $options['hide_header_searchform'] ); ?> />
                    <?php _e('Check to hide', 'interface'); ?></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Fav Icon Options', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"><label for="disable_favicon">
                      <?php _e( 'Disable Favicon', 'interface' ); ?>
                    </label></th>
                  <input type='hidden' value='0' name='interface_theme_options[disable_favicon]'>
                  <td><input type="checkbox" id="disable_favicon" name="interface_theme_options[disable_favicon]" value="1" <?php checked( '1', $options['disable_favicon'] ); ?> />
                    <?php _e('Check to disable', 'interface'); ?></td>
                </tr>
                <tr>
                  <th scope="row"><label for="fav_icon_url">
                      <?php _e( 'Fav Icon URL', 'interface' ); ?>
                    </label></th>
                  <td><input class="upload" size="65" type="text" id="fav_icon_url" name="interface_theme_options[favicon]" value="<?php echo esc_url( $options [ 'favicon' ] ); ?>" />
                    <input class="upload-button button" name="image-add" type="button" value="<?php esc_attr_e( 'Change Fav Icon', 'interface' ); ?>" /></td>
                </tr>
                <tr>
                  <th scope="row"><?php _e( 'Preview', 'interface' ); ?></th>
                  <td><?php
										       echo '<img src="'.esc_url( $options[ 'favicon' ] ).'" alt="'.__( 'favicon', 'interface' ).'" />';
										   ?></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Web Clip Icon Options', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"><label for="disable_webpageicon">
                      <?php _e( 'Disable Web Clip Icon', 'interface' ); ?>
                    </label></th>
                  <input type='hidden' value='0' name='interface_theme_options[disable_webpageicon]'>
                  <td><input type="checkbox" id="disable_webpageicon" name="interface_theme_options[disable_webpageicon]" value="1" <?php checked( '1', $options['disable_webpageicon'] ); ?> />
                    <?php _e('Check to disable', 'interface'); ?></td>
                </tr>
                <tr>
                  <th scope="row"><label for="webpageicon_icon_url">
                      <?php _e( 'Web Clip Icon URL', 'interface' ); ?>
                    </label></th>
                  <td><input class="upload" size="65" type="text" id="webpageicon_icon_url" name="interface_theme_options[webpageicon]" value="<?php echo esc_url( $options [ 'webpageicon' ] ); ?>" />
                    <input class="upload-button button" name="image-add" type="button" value="<?php esc_attr_e( 'Change Web Clip Icon', 'interface' ); ?>" /></td>
                </tr>
                <tr>
                  <th scope="row"><?php _e( 'Preview', 'interface' ); ?></th>
                  <td><?php
										       echo '<img src="'.esc_url( $options[ 'webpageicon' ] ).'" alt="'.__( 'webpage icon', 'interface' ).'" />';
										   ?></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Custom Background', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th> <?php _e( 'Need to replace default background?', 'interface' ); ?>
                  </th>
                  <td style="padding-bottom: 64px;"><?php printf(__('<a class="button" href="%s">Click here</a>', 'interface'), admin_url('themes.php?page=custom-background')); ?></td>
                  <td style="padding-bottom: 20px;"><p><small>
                      <?php _e( 'Note: The custom background change will be reflected in the background if the site layout is set to be narrow layout instead of the wide layout.', 'interface' ); ?>
                      </small></p></td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Custom CSS', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"><label for="custom-css">
                      <?php _e( 'Enter your custom CSS styles.', 'interface' ); ?>
                    </label>
                    <p><small>
                      <?php _e( 'This CSS will overwrite the CSS of style.css file.', 'interface' ); ?>
                      </small></p>
                  </th>
                  <td><textarea name="interface_theme_options[custom_css]" id="custom-css" cols="90" rows="12"><?php echo esc_attr( $options[ 'custom_css' ] ); ?></textarea></td>
                </tr>
                <tr>
                  <th scope="row"><?php _e( 'CSS Tutorial from W3Schools.', 'interface' ); ?></th>
                  <td><a class="button" href="<?php echo esc_url( __( 'http://www.w3schools.com/css/default.asp','interface' ) ); ?>" title="<?php esc_attr_e( 'CSS Tutorial', 'interface' ); ?>" target="_blank">
                    <?php _e( 'Click Here to Read', 'interface' );?>
                    </a></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container --> 
        
      </div>
      <!-- #designoptions --> 
      <!-- Options for Theme Options -->
      <div id="advancedoptions">
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Home Slogan Options', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"> <label for="slogan">
                      <?php _e( 'Disable Slogan Part', 'interface' ); ?>
                    </label>
                  </th>
                  <input type='hidden' value='0' name='interface_theme_options[disable_slogan]'>
                  <td><input type="checkbox" id="slogan" name="interface_theme_options[disable_slogan]" value="1" <?php checked( '1', $options['disable_slogan'] ); ?> />
                    <?php _e('Check to disable', 'interface'); ?></td>
                </tr>
                <tr>
                  <th scope="row"><label>
                      <?php _e( 'Slogan Position', 'interface' ); ?>
                    </label></th>
                  <td><label title="above-slider" class="box">
                      <input type="radio" name="interface_theme_options[slogan_position]" id="above-slider" <?php checked($options['slogan_position'], 'above-slider') ?> value="above-slider"  />
                      <?php _e( 'Above Slider', 'interface' ); ?>
                    </label>
                    <label title="below-slider" class="box">
                      <input type="radio" name="interface_theme_options[slogan_position]" id="below-slider" <?php checked($options['slogan_position'], 'below-slider') ?> value="below-slider"  />
                      <?php _e( 'Below Slider', 'interface' ); ?>
                    </label></td>
                </tr>
                <tr>
                  <th scope="row"><label for="slogan_1">
                      <?php _e( 'Home Page Primary Slogan', 'interface' ); ?>
                    </label>
                    <p><small>
                      <?php _e( 'The appropriate length of the slogan is around 10 words.', 'interface' ); ?>
                      </small></p>
                  </th>
                  <td><textarea class="textarea input-bg" id="slogan_1" name="interface_theme_options[home_slogan1]" cols="60" rows="3"><?php echo esc_textarea( $options[ 'home_slogan1' ] ); ?></textarea></td>
                </tr>
                <tr>
                  <th scope="row"><label for="slogan_2">
                      <?php _e( 'Home Page Secondary Slogan', 'interface' ); ?>
                    </label>
                    <p><small>
                      <?php _e( 'The appropriate length of the slogan is around 10 words.', 'interface' ); ?>
                      </small></p>
                  </th>
                  <td><textarea class="textarea input-bg" id="slogan_2" name="interface_theme_options[home_slogan2]" cols="60" rows="3"><?php echo esc_textarea( $options[ 'home_slogan2' ] ); ?></textarea></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Homepage Blog Category Setting', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row"> <label for="frontpage_posts_cats">
                      <?php _e( 'Front page posts categories:', 'interface' ); ?>
                    </label>
                    <p> <small>
                      <?php _e( 'Only posts that belong to the categories selected here will be displayed on the front page.', 'interface' ); ?>
                      </small> </p>
                  </th>
                  <td><select name="interface_theme_options[front_page_category][]" id="frontpage_posts_cats" multiple="multiple" class="select-multiple">
                      <option value="0" <?php if ( empty( $options['front_page_category'] ) ) { selected( true, true ); } ?>>
                      <?php _e( '--Disabled--', 'interface' ); ?>
                      </option>
                      <?php /* Get the list of categories */ 
                                 	if( empty( $options[ 'front_page_category' ] ) ) {
                                    	$options[ 'front_page_category' ] = array();
                                  	}
                                  	$categories = get_categories();
                                  	foreach ( $categories as $category) :?>
                      <option value="<?php echo $category->cat_ID; ?>" <?php if ( in_array( $category->cat_ID, $options['front_page_category'] ) ) {echo 'selected="selected"';}?>><?php echo $category->cat_name; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <br />
                    <span class="description">
                    <?php _e( 'You may select multiple categories by holding down the CTRL key.', 'interface' ); ?>
                    </span></td>
                </tr>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container --> 
        
      </div>
      <!-- #advancedoptions --> 
      <!-- Option for Featured Post Slier -->
      <div id="featuredpostslider"> 
        <!-- Option for More Slider Options -->
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Slider Options', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tr>
                <th scope="row"><?php _e( 'Disable Slider', 'interface' ); ?></th>
                <input type='hidden' value='0' name='interface_theme_options[disable_slider]'>
                <td><input type="checkbox" id="headerlogo" name="interface_theme_options[disable_slider]" value="1" <?php checked( '1', $options['disable_slider'] ); ?> />
                  <?php _e('Check to disable', 'interface'); ?></td>
              </tr>
              <tr>
                <th scope="row"><label>
                    <?php _e( 'Slider Content', 'interface' ); ?>
                  </label></th>
                <td><label title="on" class="box">
                    <input type="radio" name="interface_theme_options[slider_content]" id="on" <?php checked($options['slider_content'], 'on') ?> value="on"  />
                    <?php _e( 'ON <span class="description">(Slider Content will be displayed)</span>', 'interface' ); ?>
                  </label>
                  <label title="off" class="box">
                    <input type="radio" name="interface_theme_options[slider_content]" id="off" <?php checked($options['slider_content'], 'off') ?> value="off"  />
                    <?php _e( 'OFF   <span class="description">(Slider Content will not be displayed)</span>', 'interface' ); ?>
                  </label></td>
              </tr>
              <tr>
                <th scope="row"><?php _e( 'Number of Slides', 'interface' ); ?></th>
                <td><input type="text" name="interface_theme_options[slider_quantity]" value="<?php echo intval( $options[ 'slider_quantity' ] ); ?>" size="2" /></td>
              </tr>
              <tr>
                <th> <label for="interface_cycle_style">
                    <?php _e( 'Transition Effect:', 'interface' ); ?>
                  </label>
                </th>
                <td><select id="interface_cycle_style" name="interface_theme_options[transition_effect]">
                    <?php 
												$transition_effects = array();
												$transition_effects = array( 	'fade',
																						'wipe',
																						'scrollUp',
																						'scrollDown',
																						'scrollLeft',
																						'scrollRight',
																						'blindX',
																						'blindY',
																						'blindZ',
																						'cover',
																						'shuffle'
																			);
										foreach( $transition_effects as $effect ) {
											?>
                    <option value="<?php echo $effect; ?>" <?php selected( $effect, $options['transition_effect']); ?>><?php printf( __( '%s', 'interface' ), $effect ); ?></option>
                    <?php 
										}
											?>
                  </select></td>
              </tr>
              <tr>
                <th scope="row"><?php _e( 'Transition Delay', 'interface' ); ?></th>
                <td><input type="text" name="interface_theme_options[transition_delay]" value="<?php echo $options[ 'transition_delay' ]; ?>" size="2" />
                  <span class="description">
                  <?php _e( 'second(s)', 'interface' ); ?>
                  </span></td>
              </tr>
              <tr>
                <th scope="row"><?php _e( 'Transition Length', 'interface' ); ?></th>
                <td><input type="text" name="interface_theme_options[transition_duration]" value="<?php echo $options[ 'transition_duration' ]; ?>" size="2" />
                  <span class="description">
                  <?php _e( 'second(s)', 'interface' ); ?>
                  </span></td>
              </tr>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Featured Post/Page Slider Options', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tr>
                <th scope="row"><?php _e( 'Exclude Slider post from Homepage posts?', 'interface' ); ?></th>
                <input type='hidden' value='0' name='interface_theme_options[exclude_slider_post]'>
                <td><input type="checkbox" id="headerlogo" name="interface_theme_options[exclude_slider_post]" value="1" <?php checked( '1', $options['exclude_slider_post'] ); ?> />
                  <?php _e('Check to exclude', 'interface'); ?></td>
              </tr>
              <tbody class="sortable">
                <?php for ( $i = 1; $i <= $options[ 'slider_quantity' ]; $i++ ): ?>
                <tr>
                  <th scope="row"><label class="handle">
                      <?php _e( 'Featured Slider Post/Page #', 'interface' ); ?>
                      <span class="count"><?php echo absint( $i ); ?></span></label></th>
                  <td><input type="text" name="interface_theme_options[featured_post_slider][<?php echo absint( $i ); ?>]" value="<?php if( array_key_exists( 'featured_post_slider', $options ) && array_key_exists( $i, $options[ 'featured_post_slider' ] ) ) echo absint( $options[ 'featured_post_slider' ][ $i ] ); ?>" />
                    <a href="<?php bloginfo ( 'url' );?>/wp-admin/post.php?post=<?php if( array_key_exists ( 'featured_post_slider', $options ) && array_key_exists ( $i, $options[ 'featured_post_slider' ] ) ) echo absint( $options[ 'featured_post_slider' ][ $i ] ); ?>&action=edit" class="button" title="<?php esc_attr_e('Click Here To Edit'); ?>" target="_blank">
                    <?php _e( 'Click Here To Edit', 'interface' ); ?>
                    </a></td>
                </tr>
                <?php endfor; ?>
              </tbody>
            </table>
            <p>
              <?php _e( '<strong>Following are the steps on how to use the featured slider.</strong><br />* Create Post, Add featured image to the Post.<br />* Add all the Post ID that you want to use in the featured slider. <br /> &nbsp;(You can now see the Posts\' respective ID in the All Posts\' table in last column.)<br />* Featured Slider will show featured images, Title and excerpt of the respected added post\'s IDs.', 'interface' ); ?>
            </p>
            <p>
              <?php _e( '<strong>Note:</strong> You can now add Pages ID too. (You can now see the Pages\' respective ID in the All Pages\' table in last column.) .', 'interface' ); ?>
            </p>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container --> 
        
      </div>
      <!-- #featuredpostslider --> 
      <!-- Option for Design Settings -->
      <div id="sociallink">
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Contact Info Bar', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tr>
                <th scope="row" style="padding: 0px;"><h4>
                    <?php _e( 'Disable Top Info Bar', 'interface' ); ?>
                  </h4></th>
                <input type='hidden' value='0' name='interface_theme_options[disable_top]'>
                <td><input type="checkbox" id="disable_top" name="interface_theme_options[disable_top]" value="1" <?php checked( '1', $options['disable_top'] ); ?> />
                  <?php _e('Check to disable', 'interface'); ?></td>
              </tr>
              <tr>
                <th scope="row" style="padding: 0px;"><h4>
                    <?php _e( 'Disable Bottom Info Bar', 'interface' ); ?>
                  </h4></th>
                <input type='hidden' value='0' name='interface_theme_options[disable_bottom]'>
                <td><input type="checkbox" id="disable_bottom" name="interface_theme_options[disable_bottom]" value="1" <?php checked( '1', $options['disable_bottom'] ); ?> />
                  <?php _e('Check to disable', 'interface'); ?></td>
              </tr>
              <tr>
                <th scope="row" style="padding: 0px;"><h4>
                    <?php _e( 'Phone Number', 'interface' ); ?>
                  </h4></th>
                <td><input type="text" size="45" name="interface_theme_options[social_phone]" value="<?php echo  preg_replace("/[^() 0-9+-]/", '', $options[ 'social_phone' ]) ; ?>" />
                  <?php _e('Enter your Phone number only', 'interface'); ?></td>
              </tr>
              <tr>
                <th scope="row" style="padding: 0px;"><h4>
                    <?php _e( 'Email ID Only', 'interface' ); ?>
                  </h4></th>
                <td><input type="text" size="45" name="interface_theme_options[social_email]" value="<?php echo  is_email($options[ 'social_email'] ); ?>" />
                  <?php _e('Enter your Email ID', 'interface'); ?></td>
              </tr>
              <tr>
                <th scope="row" style="padding: 0px;"><h4>
                    <?php _e( 'Location Only', 'interface' ); ?>
                  </h4></th>
                <td><input type="text" size="45" name="interface_theme_options[social_location]" value="<?php echo  esc_attr($options[ 'social_location']); ?>" />
                  <?php _e('Enter your Address', 'interface'); ?></td>
              </tr>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
          <!-- .option-content --> 
        </div>
        <!-- .option-container -->
        
        <?php 
						$social_links = array(); 
						$social_links_name = array();
						$social_links_name = array( __( 'Facebook', 'interface' ),
													__( 'Twitter', 'interface' ),
													__( 'Google Plus', 'interface' ),
													__( 'Pinterest', 'interface' ),
													__( 'Youtube', 'interface' ),
													__( 'Vimeo', 'interface' ),
													__( 'LinkedIn', 'interface' ),
													__( 'Flickr', 'interface' ),
													__( 'Tumblr', 'interface' ),
													__( 'RSS', 'interface' )
													);
						$social_links = array( 	'Facebook' 		=> 'social_facebook',
														'Twitter' 		=> 'social_twitter',
														'Google-Plus'	=> 'social_googleplus',
														'Pinterest' 	=> 'social_pinterest',
														'You-tube'		=> 'social_youtube',
														'Vimeo'			=> 'social_vimeo',
														'linkedin'			=> 'social_linkedin',
														'Flickr'			=> 'social_flickr',
														'Tumblr'			=> 'social_tumblr',
														'RSS'				=> 'social_rss' 
													);
					?>
        <div class="option-container">
          <h3 class="option-toggle"><a href="#">
            <?php _e( 'Social Links', 'interface' ); ?>
            </a></h3>
          <div class="option-content inside">
            <table class="form-table">
              <tbody>
                <?php
						$i = 0;
						foreach( $social_links as $key => $value ) {
						?>
                <tr>
                  <th scope="row" style="padding: 0px;"><h4><?php printf( __( '%s', 'interface' ), $social_links_name[ $i ] ); ?></h4></th>
                  <td><input type="text" size="45" name="interface_theme_options[<?php echo $value; ?>]" value="<?php echo esc_url( $options[$value] ); ?>" /></td>
                </tr>
                <?php
						$i++;
						}
						?>
              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'interface' ); ?>" />
            </p>
          </div>
        </div>
      </div>
      <!-- #sociallink --> 
    </div>
    <!-- #interface_tabs -->
  </form>
</div>
<!-- .wrap -->
<?php
}

/****************************************************************************************/

/**
 * Validate all theme options values
 * 
 * @uses esc_url_raw, absint, esc_textarea, sanitize_text_field, interface_invalidate_caches
 */
function interface_theme_options_validate( $options ) { //validate individual options before saving. using register_setting 3rd parameter interface_theme_options_validate
	global $interface_theme_default, $interface_default;
	$validated_input_values = $interface_theme_default;
	$input = array();
	$input = $options;

	if ( isset( $input[ 'header_logo' ] ) ) {
		$validated_input_values[ 'header_logo' ] = esc_url_raw( $input[ 'header_logo' ] );
	}
										//esc_url_raw -> To save at the databaseSSSS
										// esc_url -> to echo the url
										//sanitize_text_field -> for normal text only if you dont want html text.
	if( isset( $input[ 'header_show' ] ) ) {
		$validated_input_values[ 'header_show' ] = $input[ 'header_show' ];
	}

   if ( isset( $options[ 'hide_header_searchform' ] ) ) {
		$validated_input_values[ 'hide_header_searchform' ] = $input[ 'hide_header_searchform' ];
	}
    
	if ( isset( $options[ 'disable_slogan' ] ) ) {
		$validated_input_values[ 'disable_slogan' ] = $input[ 'disable_slogan' ];
	}

	if( isset( $options[ 'home_slogan1' ] ) ) {
		$validated_input_values[ 'home_slogan1' ] = sanitize_text_field( $input[ 'home_slogan1' ] );
	}

	if( isset( $options[ 'home_slogan2' ] ) ) {
		$validated_input_values[ 'home_slogan2' ] = sanitize_text_field( $input[ 'home_slogan2' ] );
	}

	if( isset( $input[ 'slogan_position' ] ) ) {
		$validated_input_values[ 'slogan_position' ] = $input[ 'slogan_position' ];
	}	

	if( isset( $options[ 'button_text' ] ) ) {
		$validated_input_values[ 'button_text' ] = sanitize_text_field( $input[ 'button_text' ] );
	}

	if( isset( $options[ 'redirect_button_link' ] ) ) {
		$validated_input_values[ 'redirect_button_link' ] = esc_url_raw( $input[ 'redirect_button_link' ] );
	}
        
	if ( isset( $input[ 'favicon' ] ) ) {
		$validated_input_values[ 'favicon' ] = esc_url_raw( $input[ 'favicon' ] );
	}

	if ( isset( $input['disable_favicon'] ) ) {
		$validated_input_values[ 'disable_favicon' ] = $input[ 'disable_favicon' ];
	}

	if ( isset( $input[ 'webpageicon' ] ) ) {
		$validated_input_values[ 'webpageicon' ] = esc_url_raw( $input[ 'webpageicon' ] );
	}

	if ( isset( $input['disable_webpageicon'] ) ) {
		$validated_input_values[ 'disable_webpageicon' ] = $input[ 'disable_webpageicon' ];
	}

	//Site Layout
	if( isset( $input[ 'site_layout' ] ) ) {
		$validated_input_values[ 'site_layout' ] = $input[ 'site_layout' ];
	}

   // Front page posts categories
	if( isset( $input['front_page_category' ] ) ) {
		$validated_input_values['front_page_category'] = $input['front_page_category'];
	}
    
	// Data Validation for Featured Slider
	if( isset( $input[ 'disable_slider' ] ) ) {
		$validated_input_values[ 'disable_slider' ] = $input[ 'disable_slider' ];
	}

	if ( isset( $input[ 'slider_quantity' ] ) ) {
		$validated_input_values[ 'slider_quantity' ] = absint( $input[ 'slider_quantity' ] ) ? $input [ 'slider_quantity' ] : 4;
	}
	if ( isset( $input['exclude_slider_post'] ) ) {
		$validated_input_values[ 'exclude_slider_post' ] = $input[ 'exclude_slider_post' ];	

	}
	if ( isset( $input[ 'featured_post_slider' ] ) ) {
		$validated_input_values[ 'featured_post_slider' ] = array();
	}   
	if( isset( $input[ 'slider_quantity' ] ) )   
	for ( $i = 1; $i <= $input [ 'slider_quantity' ]; $i++ ) {
		if ( intval( $input[ 'featured_post_slider' ][ $i ] ) ) {
			$validated_input_values[ 'featured_post_slider' ][ $i ] = absint($input[ 'featured_post_slider' ][ $i ] );
		}
	}  
	
	
	
   // data validation for transition effect
	if( isset( $input[ 'transition_effect' ] ) ) {
		$validated_input_values['transition_effect'] = wp_filter_nohtml_kses( $input['transition_effect'] );
	}

	// data validation for transition delay
	if ( isset( $input[ 'transition_delay' ] ) && is_numeric( $input[ 'transition_delay' ] ) ) {
		$validated_input_values[ 'transition_delay' ] = $input[ 'transition_delay' ];
	}

	// data validation for transition length
	if ( isset( $input[ 'transition_duration' ] ) && is_numeric( $input[ 'transition_duration' ] ) ) {
		$validated_input_values[ 'transition_duration' ] = $input[ 'transition_duration' ];
	}
    
   // data validation for Social Icons

   if ( isset( $input['disable_top'] ) ) {
		$validated_input_values[ 'disable_top' ] = $input[ 'disable_top' ];
	}
	 if ( isset( $input['disable_bottom'] ) ) {
		$validated_input_values[ 'disable_bottom' ] = $input[ 'disable_bottom' ];
	}
   if ( isset( $input[ 'social_phone' ] ) ) {
		$validated_input_values[ 'social_phone' ] = preg_replace("/[^() 0-9+-]/", '', $options[ 'social_phone' ]);
	}

	if( isset( $input[ 'social_email' ] ) ) {
		$validated_input_values[ 'social_email' ] = sanitize_email( $input[ 'social_email' ] );
	}
	if( isset( $input[ 'social_location' ] ) ) {
		$validated_input_values[ 'social_location' ] = sanitize_text_field( $input[ 'social_location' ] );
	}

	if( isset( $input[ 'social_facebook' ] ) ) {
		$validated_input_values[ 'social_facebook' ] = esc_url_raw( $input[ 'social_facebook' ] );
	}
	if( isset( $input[ 'social_twitter' ] ) ) {
		$validated_input_values[ 'social_twitter' ] = esc_url_raw( $input[ 'social_twitter' ] );
	}
	if( isset( $input[ 'social_googleplus' ] ) ) {
		$validated_input_values[ 'social_googleplus' ] = esc_url_raw( $input[ 'social_googleplus' ] );
	}
	if( isset( $input[ 'social_pinterest' ] ) ) {
		$validated_input_values[ 'social_pinterest' ] = esc_url_raw( $input[ 'social_pinterest' ] );
	}   
	if( isset( $input[ 'social_youtube' ] ) ) {
		$validated_input_values[ 'social_youtube' ] = esc_url_raw( $input[ 'social_youtube' ] );
	}
	if( isset( $input[ 'social_vimeo' ] ) ) {
		$validated_input_values[ 'social_vimeo' ] = esc_url_raw( $input[ 'social_vimeo' ] );
	}   
	if( isset( $input[ 'social_linkedin' ] ) ) {
		$validated_input_values[ 'social_linkedin' ] = esc_url_raw( $input[ 'social_linkedin' ] );
	}
	if( isset( $input[ 'social_flickr' ] ) ) {
		$validated_input_values[ 'social_flickr' ] = esc_url_raw( $input[ 'social_flickr' ] );
	}
	if( isset( $input[ 'social_tumblr' ] ) ) {
		$validated_input_values[ 'social_tumblr' ] = esc_url_raw( $input[ 'social_tumblr' ] );
	}   
	if( isset( $input[ 'social_myspace' ] ) ) {
		$validated_input_values[ 'social_myspace' ] = esc_url_raw( $input[ 'social_myspace' ] );
	}  
	if( isset( $input[ 'social_rss' ] ) ) {
		$validated_input_values[ 'social_rss' ] = esc_url_raw( $input[ 'social_rss' ] );
	}   

	//Custom CSS Style Validation
	if ( isset( $input['custom_css'] ) ) {
		$validated_input_values['custom_css'] = wp_kses_stripslashes($input['custom_css']);
	}

	if( isset( $input[ 'site_design' ] ) ) {
		$validated_input_values[ 'site_design' ] = $input[ 'site_design' ];
	}   
	
	if( isset( $input[ 'slider_content' ] ) ) {
		$validated_input_values[ 'slider_content' ] = $input[ 'slider_content' ];
	} 
    
	// Layout settings verification
	if( isset( $input[ 'reset_layout' ] ) ) {
		$validated_input_values[ 'reset_layout' ] = $input[ 'reset_layout' ];
	}
	if( 0 == $validated_input_values[ 'reset_layout' ] ) {
		if( isset( $input[ 'default_layout' ] ) ) {
			$validated_input_values[ 'default_layout' ] = $input[ 'default_layout' ];
		}
	}
	else {
		$validated_input_values['default_layout'] = $interface_default[ 'default_layout' ];
	}

	
    
	
    
   return $validated_input_values;
}
function interface_themeoption_invalidate_caches(){
	
	delete_transient( 'interface_socialnetworks' );  
	
}

/*	 _e() -> to echo the text
*	 __() -> to get the value
*	 printf () -> to echo the value eg:- my name is $name
*	 eg:- printf( __( 'Your city is %1$s, and your zip code is %2$s.', 'my-text-domain' ), $city, $zipcode );
*	 sprintf() - > to get the value 
* 	 eg:- $url = 'http://example.com';
*	 $link = sprintf( __( 'Check out this link to my <a href="%s">website</a> made with WordPress.', 'my-text-domain' ), esc_url( $url ) );
*	 echo $link;
*/

?>
