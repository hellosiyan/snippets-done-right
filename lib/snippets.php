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
		'public' => true,
		'rewrite' => array(
			'slug' => 'snippet'
		),
		'capability_type' => 'post',
		'exclude_from_search' => true,
		'supports' => array( 'title', 'revisions' )
	);

	register_post_type( 'wippet_snippet', $args );
}

function wippets_snippet_get_language( $snippet_id ) {
	if ( is_object( $snippet_id ) ) {
		if ( empty( $snippet_id->ID ) ) {
			return false;
		}

		$snippet_id = $snippet_id->ID;
	}

	$language = get_post_meta( $snippet_id, '_wippets_language', true );
	$language = empty( $language ) ? 'text': $language;

	return $language;
}

function wippets_display_code_editor( $post ) {
	$screen = get_current_screen();

	if ( $screen->base !== 'post' || $screen->id !== 'wippet_snippet' ) {
		return;
	}

	$snippet_language = wippets_snippet_get_language( $post->ID );

	include( WIPPETS_PATH . '/templates/content-editor.php' );
}

function wippets_snippet_on_save( $post_id ) {
	if ( ! isset( $_POST['wippets_snippet_editor'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['wippets_snippet_editor'], 'wippets_snippet_editor' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	

	if ( isset( $_POST['wippets_language'] ) ) {
		$snippet_language = sanitize_text_field( $_POST['wippets_language'] );
		update_post_meta( $post_id, '_wippets_language', $snippet_language );
	}
}
