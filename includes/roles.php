<?php

/**
 * Register Task Logger role
 */
function taskbook_register_role() {
	add_role( 'task_logger', 'Task Logger' );
}

/**
 * Register Task Logger role
 */
function taskbook_remove_role() {
	remove_role( 'task_logger' );
}

/**
 * Grant Task-Level capabilities to Administrator, Editor, and Task Logger.
 */
add_action( 'init', 'taskbook_add_capabilities' );
function taskbook_add_capabilities() {

	$roles = [ 'administrator', 'editor', 'task_logger' ];
	foreach ( $roles as $the_role ) {
		$role = get_role( $the_role );

		$role->add_cap( 'read' );
		$role->add_cap( 'edit_tasks' );
		$role->add_cap( 'publish_tasks' );
		$role->add_cap( 'edit_published_tasks' );
	}

	$manager_roles = [ 'administrator' ];
	foreach ( $manager_roles as $the_role ) {
		$role = get_role( $the_role );

		$role->add_cap( 'read_private_tasks' );
		$role->add_cap( 'edit_others_tasks' );
		$role->add_cap( 'edit_private_tasks' );
		$role->add_cap( 'delete_tasks' );
		$role->add_cap( 'delete_published_tasks' );
		$role->add_cap( 'delete_others_tasks' );
		$role->add_cap( 'delete_private_tasks' );
	}
}

function taskbook_remove_capabilities() {

	$manager_roles = [ 'administrator', 'editor' ]; // Don't need to add task_logger because it will be deleted
	foreach ( $manager_roles as $the_role ) {
		$role = get_role( $the_role );

		$role->remove_cap( 'read' );
		$role->remove_cap( 'edit_tasks' );
		$role->remove_cap( 'publish_tasks' );
		$role->remove_cap( 'edit_published_tasks' );
		$role->remove_cap( 'read_private_tasks' );
		$role->remove_cap( 'edit_others_tasks' );
		$role->remove_cap( 'edit_private_tasks' );
		$role->remove_cap( 'delete_tasks' );
		$role->remove_cap( 'delete_published_tasks' );
		$role->remove_cap( 'delete_others_tasks' );
		$role->remove_cap( 'delete_private_tasks' );
	}
}