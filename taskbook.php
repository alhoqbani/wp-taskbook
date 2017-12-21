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