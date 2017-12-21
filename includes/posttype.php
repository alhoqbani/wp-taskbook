<?php
add_action( 'init', 'taskbook_cpt_init' );
/**
 * Register a task post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function taskbook_cpt_init() {
	$labels = [
		'name'               => _x( 'Tasks', 'post type general name', 'taskbook' ),
		'singular_name'      => _x( 'Task', 'post type singular name', 'taskbook' ),
		'menu_name'          => _x( 'Tasks', 'admin menu', 'taskbook' ),
		'name_admin_bar'     => _x( 'Task', 'add new on admin bar', 'taskbook' ),
		'add_new'            => _x( 'Add New', 'task', 'taskbook' ),
		'add_new_item'       => __( 'Add New Task', 'taskbook' ),
		'new_item'           => __( 'New Task', 'taskbook' ),
		'edit_item'          => __( 'Edit Task', 'taskbook' ),
		'view_item'          => __( 'View Task', 'taskbook' ),
		'all_items'          => __( 'All Tasks', 'taskbook' ),
		'search_items'       => __( 'Search Tasks', 'taskbook' ),
		'parent_item_colon'  => __( 'Parent Tasks:', 'taskbook' ),
		'not_found'          => __( 'No tasks found.', 'taskbook' ),
		'not_found_in_trash' => __( 'No tasks found in Trash.', 'taskbook' ),
	];

	$args = [
		'labels'             => $labels,
		'description'        => __( 'Description.', 'taskbook' ),
		'public'             => false, // Make private, does not appear in frontend of wordpress
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'task' ],
		'capability_type'    => 'task', // We chose to name the capability 'task', will be used when adding caps to roles.
		'map_meta_cap'       => true, // Map default Post capabilities to the Task post type
		'has_archive'        => true,
		'hierarchical'       => false,
		'show_in_rest'       => true,
		'rest_base'          => 'tasks',
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-exerpt-view',
		'supports'           => [ 'title', 'editor', 'author' ],
	];

	register_post_type( 'task', $args );
}

add_filter( 'post_updated_messages', 'taskbook_task_updated_messages' );

/**
 * Task update messages.
 *
 * See /wp-admin/edit-form-advanced.php
 *
 * @param array $messages Existing post update messages.
 *
 * @return array Amended post update messages with new CPT update messages.
 */
function taskbook_task_updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['task'] = [
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Task updated.', 'taskbook' ),
		2  => __( 'Custom field updated.', 'taskbook' ),
		3  => __( 'Custom field deleted.', 'taskbook' ),
		4  => __( 'Task updated.', 'taskbook' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Task restored to revision from %s', 'taskbook' ),
			wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Task published.', 'taskbook' ),
		7  => __( 'Task saved.', 'taskbook' ),
		8  => __( 'Task submitted.', 'taskbook' ),
		9  => sprintf(
			__( 'Task scheduled for: <strong>%1$s</strong>.', 'taskbook' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'taskbook' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Task draft updated.', 'taskbook' ),
	];

	if ( $post_type_object->publicly_queryable && 'task' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link                 = sprintf( ' <a href="%s">%s</a>',
			esc_url( $permalink ),
			__( 'View task', 'taskbook' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink          = add_query_arg( 'preview', 'true', $permalink );
		$preview_link               = sprintf( ' <a target="_blank" href="%s">%s</a>',
			esc_url( $preview_permalink ),
			__( 'Preview task', 'taskbook' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}

// Attached to register_activation_hook in main file
function taskbook_flush_rewrites() {
	// call your CPT registration function here (it should also be hooked into 'init')
	taskbook_cpt_init();
	flush_rewrite_rules();
}

