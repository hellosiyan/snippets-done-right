<?php

function wippets_admin_init() {

	# Add Actions

	add_action( 'media_buttons', 'wippets_buttons_embed_snippet', 11 );
	add_action( 'edit_form_after_title', 'wippets_display_code_editor' );
	add_action( 'load-edit.php', 'wippets_embed_snippet_screen' );
	add_action( 'admin_menu', 'wippets_add_settings_page' );

	add_action( 'admin_enqueue_scripts', 'wippets_enqueue_ace' );
	add_action( 'admin_enqueue_scripts', 'wippets_admin_register_scripts' );
}

function wippets_admin_register_scripts() {
	$screen = get_current_screen();

	wp_register_style( 'wippets-style-admin', WIPPETS_URL . '/assets/wippets-admin.css', array( ), WIPPETS_VERSION );
	wp_register_script( 'wippets-functions-admin', WIPPETS_URL . '/assets/wippets-admin.js', array( 'jquery' ), WIPPETS_VERSION );

	// By default enqueue on snippet edit screen only
	if ( $screen->base === 'post' && $screen->id === 'wippet_snippet' ) {
		wippets_admin_enqueue_scripts();	
	}
}

function wippets_admin_enqueue_scripts() {
	wp_enqueue_style( 'wippets-style-admin' );
	wp_enqueue_script( 'wippets-functions-admin' );

	// Pass plugin options to our javascript
	$options = wippets_get_options();
	wp_localize_script( 'wippets-functions-admin', 'wippets_options', $options );
}

function wippets_buttons_embed_snippet($editor_id = 'content') {
	wp_enqueue_style( 'wippets-style-admin' );
	wp_enqueue_script( 'wippets-functions-admin' );

	$embed_snippet_screen = add_query_arg( array(
		'post_type' => 'wippet_snippet',
		'wippets-view-embed-script' => 'true'
	), admin_url( 'edit.php' ) );

	echo '<a href="' . esc_url( $embed_snippet_screen ) . '" id="wippets-embed-snippet-button" class="button thickbox" data-editor="' . esc_attr( $editor_id ) . '" title="' . esc_attr__( 'Embed Snippet', 'wippets' ) . '">' . __( 'Embed Snippet', 'wippets' ) . '</a>';
}

function wippets_embed_snippet_screen() {
	if ( ! isset( $_GET['wippets-view-embed-script'] ) || $_GET['wippets-view-embed-script'] !== 'true' ) {
		return;
	}

	$snippets = get_posts(array(
		'post_type' => 'wippet_snippet',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC'
	));

	include( WIPPETS_PATH . '/templates/embed-snippet-screen.php' );
	exit;
}

function wippets_add_settings_page() {
	$settings_page_name = add_submenu_page(
		'edit.php?post_type=wippet_snippet',
		'Wippets Settings',
		'Settings',
		'manage_options',
		'wippets-settings',
		'wippets_render_settings_page'
	);

	add_action('load-' . $settings_page_name, 'wippets_load_settings_page');
}

function wippets_load_settings_page() {
	if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
		add_action( 'admin_enqueue_scripts', 'wippets_admin_enqueue_scripts' );
		return;
	}

	if ( !isset( $_POST['wippets_settings'] ) ) {
		return;
	}

	check_admin_referer( 'wippets_settings', 'wippets_settings' );

	$new_options = wippets_get_options();

	// Color Theme
	if ( isset( $_POST['wippets_ace_theme'] ) ) {
		$new_options['ace_theme'] = sanitize_key( $_POST['wippets_ace_theme'] );
	}

	foreach ($new_options as $option_key => $option_value) {
		update_option( 'wippets_' . $option_key, $option_value );
	}

	wp_redirect( add_query_arg(array()) );
	exit;
}

function wippets_render_settings_page() {
	$options = wippets_get_options();

	include( WIPPETS_PATH . '/templates/settings.php' );
}