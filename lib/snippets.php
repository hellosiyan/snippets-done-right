<?php

function sdr_register_post_types() {
	$args = array(
		'labels' => array(
			'name' => __( 'Snippets', 'post type general name', 'sdr' ),
			'singular_name' => __( 'Snippet', 'post type singular name', 'sdr' ),
			'add_new_item' => __( 'Add New Snippet', 'sdr' ),
			'new_item' => __( 'New Snippet', 'sdr' ),
			'edit_item' => __( 'Edit Snippet', 'sdr' ),
			'view_item' => __( 'View Snippet', 'sdr' ),
			'all_items' => __( 'All Snippets', 'sdr' ),
			'search_items' => __( 'Search Snippets', 'sdr' ),
			'parent_item_colon' => __( 'Parent Snippets:', 'sdr' ),
			'not_found' => __( 'No snippets found.', 'sdr' ),
			'not_found_in_trash' => __( 'No snippets found in Trash.', 'sdr' ),
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
