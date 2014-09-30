<?php
/**
 * Adds footer structures.
 *
 * @package 		Theme Horse
 * @subpackage 		Interface
 * @since 			Interface 1.0
 * @license 		http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link 			http://themehorse.com/themes/interface
 */

/****************************************************************************************/
global $interface_theme_setting_value;
		$options = $interface_theme_setting_value;
add_action( 'interface_footer', 'interface_footer_widget_area', 5 );
/** 
 * Displays the footer widgets
 */
function interface_footer_widget_area() {
	get_sidebar( 'footer' );
}

/****************************************************************************************/
if ((1 != $options['disable_bottom']) && (!empty($options['social_phone'] ) || !empty($options['social_email'] ) || !empty($options['social_location']))) {
add_action( 'interface_footer', 'interface_footer_infoblog', 10 );
/**
 * Opens the footer infobox
 */
/****************************************************************************************/

add_action( 'interface_footer', 'interface_footer_div_close', 15 );
/**
 * Opens the site generator div.
 */
function interface_footer_div_close() {
	echo '</div> <!-- .container -->

	</div> <!-- .info-bar -->';
	} 
}
/****************************************************************************************/

add_action( 'interface_footer', 'interface_open_sitegenerator_div', 20 );
/**
 * Opens the site generator div.
 */
function interface_open_sitegenerator_div() {
	echo '

	<div id="site-generator">
				<div class="container clearfix">';
}

	/****************************************************************************************/


add_action( 'interface_footer', 'interface_socialnetworks', 25 );



/****************************************************************************************/

add_action( 'interface_footer', 'interface_footer_info', 30 );
/**
 * function to show the footer info, copyright information
 */
function interface_footer_info() {         
   $output = '<div class="copyright">'.__( 'Copyright &copy;', 'interface' ).' '.'[the-year] [site-link]'.' '.__( 'Theme by:', 'interface' ).' '.'[th-link]'.' '.__( 'Powered by:', 'interface' ).' '.'[wp-link] '.'</div><!-- .copyright -->';
   echo do_shortcode( $output );
}
/****************************************************************************************/

add_action( 'interface_footer', 'interface_close_sitegenerator_div', 35 );
/**
 * Shows the back to top icon to go to top.
 */
function interface_close_sitegenerator_div() {
echo '</div><!-- .container -->	
			</div><!-- #site-generator -->';
}

/****************************************************************************************/

add_action( 'interface_footer', 'interface_backtotop_html', 40 );
/**
 * Shows the back to top icon to go to top.
 */
function interface_backtotop_html() {
	echo '<div class="back-to-top"><a href="#branding">'.__( ' ', 'interface' ).'</a></div>';
}

?>