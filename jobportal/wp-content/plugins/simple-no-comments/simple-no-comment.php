<?php
/*
Plugin Name: Simple No Comment
Plugin URI: http://www.aconix-infotech.com/
Description: This plugin is created to remove comment from everywhere
Author: Jitendra Singh Dikhit
Version: 1.0
Author URI: http://www.jitendrasinghdikhit.com
Author Email: jitendra5984@gmail.com
*/

/*action to call removing comment link function */
add_action( 'admin_menu', 'simple_remove_admin_menus' );

/*function to remove left side link in admin area*/
function simple_remove_admin_menus() {
	remove_menu_page( 'edit-comments.php' );
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}

/* function to set comment_status closed to remove comments from everywhere */
function simple_no_comment()
{
	global $wpdb;
	if($wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE `comment_status`!='closed' ")>0)
	{
	$wpdb->query("UPDATE $wpdb->posts SET `comment_status` = 'closed'");
	$wpdb->query("UPDATE $wpdb->comments SET `comment_approved` = '0'");
	}
}

/*calling simple_no_comment function */
add_action('init', 'simple_no_comment');

/* function for setting comment_status open for all posts and pages*/
function simple_no_comment_deactivation() {
	global $wpdb;
	$wpdb->query("UPDATE $wpdb->posts SET comment_status = 'open'");
}

/* function to set basic settings before deactivation of plugin */
register_deactivation_hook( __FILE__, 'simple_no_comment_deactivation' );

?>