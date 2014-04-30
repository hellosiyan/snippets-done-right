<?php

function wippets_register_post_types() {
	$args = array(
		'labels' => array(
			'name' => __( 'Snippets', 'post type general name', 'wippets' ),
			'singular_name' => __( 'Snippet', 'post type singular name', 'wippets' ),
			'add_new_item' => __( 'Add New Snippet', 'wippets' ),
			'new_item' => __( 'New Snippet', 'wippets' ),
			'edit_item' => __( 'Edit Snippet', 'wippets' ),
			'view_item' => __( 'View Snippet', 'wippets' ),
			'all_items' => __( 'All Snippets', 'wippets' ),
			'search_items' => __( 'Search Snippets', 'wippets' ),
			'parent_item_colon' => __( 'Parent Snippets:', 'wippets' ),
			'not_found' => __( 'No snippets found.', 'wippets' ),
			'not_found_in_trash' => __( 'No snippets found in Trash.', 'wippets' ),
		),
		'public'             => true,
		'rewrite'            => array(
			'slug' => 'snippet'
		),
		'capability_type'    => 'post',
		'supports'           => array( 'title', 'revisions' )
	);

	register_post_type( 'wippet_snippet', $args );
}

function wippets_display_code_editor( $post ) {
	$screen = get_current_screen();

	if ( $screen->base !== 'post' || $screen->id !== 'wippet_snippet' ) {
		return;
	}

	include( WIPPETS_PATH . '/templates/content-editor.php' );
}