<?php

function sdr_register_post_types() {
	$args = array(
		'labels' => array(
			'name' => __( 'Snippets', 'snippets-done-right' ),
			'singular_name' => __( 'Snippet', 'snippets-done-right' ),
			'add_new_item' => __( 'Add New Snippet', 'snippets-done-right' ),
			'new_item' => __( 'New Snippet', 'snippets-done-right' ),
			'edit_item' => __( 'Edit Snippet', 'snippets-done-right' ),
			'view_item' => __( 'View Snippet', 'snippets-done-right' ),
			'all_items' => __( 'All Snippets', 'snippets-done-right' ),
			'search_items' => __( 'Search Snippets', 'snippets-done-right' ),
			'parent_item_colon' => __( 'Parent Snippets:', 'snippets-done-right' ),
			'not_found' => __( 'No snippets found.', 'snippets-done-right' ),
			'not_found_in_trash' => __( 'No snippets found in Trash.', 'snippets-done-right' ),
		),
		'public' => false,
		'show_ui' => true,
		'rewrite' => array(
			'slug' => 'snippet'
		),
		'capability_type' => 'post',
		'supports' => array( 'title', 'revisions' )
	);

	register_post_type( 'sdr_snippet', $args );
}

function sdr_snippet_get_language( $snippet_id ) {
	if ( is_object( $snippet_id ) ) {
		if ( empty( $snippet_id->ID ) ) {
			return false;
		}

		$snippet_id = $snippet_id->ID;
	}

	$language = get_post_meta( $snippet_id, '_sdr_language', true );
	$language = empty( $language ) ? 'text': $language;

	return $language;
}

function sdr_display_code_editor( $post ) {
	$screen = get_current_screen();

	if ( $screen->base !== 'post' || $screen->id !== 'sdr_snippet' ) {
		return;
	}

	$snippet_language = sdr_snippet_get_language( $post->ID );

	include( SDR_PATH . '/templates/content-editor.php' );
}

function sdr_snippet_on_save( $post_id ) {
	if ( ! isset( $_POST['sdr_snippet_editor'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['sdr_snippet_editor'], 'sdr_snippet_editor' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	

	if ( isset( $_POST['sdr_language'] ) ) {
		$snippet_language = sanitize_text_field( $_POST['sdr_language'] );
		update_post_meta( $post_id, '_sdr_language', $snippet_language );
	}
}
