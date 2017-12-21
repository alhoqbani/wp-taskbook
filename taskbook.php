<?php

/**
 * Plugin Name: Task Book
 * Description: Task Book REST API
 * Author: Hamoud Alhoqbani
 * Author URI: https://github.com/alhoqbani
 * Version: 0.1
 * Text Domain: taskbook
 */

/**
 * Register Taskbook posttype
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/posttype.php';

/**
 * Register a task post type.
 */
register_activation_hook( __FILE__, 'taskbook_flush_rewrites' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

/**
 * Register a task logger role.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/roles.php';
register_activation_hook( __FILE__, 'taskbook_register_role' );
/**
 * Add Task capabilities
 */
register_activation_hook( __FILE__, 'taskbook_add_capabilities' );
register_deactivation_hook( __FILE__, 'taskbook_remove_role' );
register_deactivation_hook( __FILE__, 'taskbook_remove_capabilities' );

/**
 * Auto-update the Status field on Save Post
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/status.php';

/**
 * Add CBM2 Plugin To Create Custom Meta Data Boxes
 */
// require the full CMB2 example file which only applies to page post type.
//require_once plugin_dir_path( __FILE__ ) . 'includes/CMB2-example-functions.php';

// require only the fields related to the Task post type
require_once plugin_dir_path( __FILE__ ) . 'includes/CMB2-task-functions.php';

/**
 * Grant task access for index pages for certain users.
 */
add_action( 'pre_get_posts', 'taskbook_grant_access' );
/**
 * @param WP_Query $query
 */
function taskbook_grant_access( $query ) {
	if ( isset( $query->query_vars['post_type'] ) ) {
		if ( $query->query_vars['post_type'] == 'task' ) {
			if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
				if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
					$query->set( 'post_status', 'private' );
				} elseif ( current_user_can( 'task_logger' ) ) {
					$query->set( 'post_status', 'private' );
					$query->set( 'author', get_current_user_id() );
				}
			}
		}
	}
}
