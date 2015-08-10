<?php

function sdr_admin_init() {

	# Add Actions

	add_action( 'media_buttons', 'sdr_buttons_embed_snippet', 11 );
	add_action( 'edit_form_after_title', 'sdr_display_code_editor' );
	add_action( 'load-edit.php', 'sdr_embed_snippet_screen' );
	add_action( 'admin_menu', 'sdr_add_settings_page' );

	add_action( 'admin_enqueue_scripts', 'sdr_enqueue_ace' );
	add_action( 'admin_enqueue_scripts', 'sdr_admin_register_scripts' );
}

function sdr_admin_register_scripts() {
	$screen = get_current_screen();

	wp_register_style( 'sdr-style-admin', SDR_URL . '/assets/sdr-admin.css', array( ), SDR_VERSION );
	wp_register_script( 'sdr-functions-admin', SDR_URL . '/assets/sdr-admin.js', array( 'jquery' ), SDR_VERSION );

	// By default enqueue on snippet edit screen only
	if ( $screen->base === 'post' && $screen->id === 'sdr_snippet' ) {
		sdr_admin_enqueue_scripts();	
	}
}

function sdr_admin_enqueue_scripts() {
	wp_enqueue_style( 'sdr-style-admin' );
	wp_enqueue_script( 'sdr-functions-admin' );

	// Pass plugin options to our javascript
	$options = sdr_get_options();
	wp_localize_script( 'sdr-functions-admin', 'sdr_options', $options );
}

function sdr_buttons_embed_snippet($editor_id = 'content') {
	wp_enqueue_style( 'sdr-style-admin' );
	wp_enqueue_script( 'sdr-functions-admin' );

	$embed_snippet_screen = add_query_arg( array(
		'post_type' => 'sdr_snippet',
		'sdr-view-embed-script' => 'true'
	), admin_url( 'edit.php' ) );

	echo '<a href="' . esc_url( $embed_snippet_screen ) . '" id="sdr-embed-snippet-button" class="button thickbox" data-editor="' . esc_attr( $editor_id ) . '" title="' . esc_attr__( 'Embed Snippet', 'sdr' ) . '">' . __( 'Embed Snippet', 'sdr' ) . '</a>';
}

function sdr_embed_snippet_screen() {
	if ( ! isset( $_GET['sdr-view-embed-script'] ) || $_GET['sdr-view-embed-script'] !== 'true' ) {
		return;
	}

	$snippets = get_posts(array(
		'post_type' => 'sdr_snippet',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC'
	));

	include( SDR_PATH . '/templates/embed-snippet-screen.php' );
	exit;
}

function sdr_add_settings_page() {
	$settings_page_name = add_submenu_page(
		'edit.php?post_type=sdr_snippet',
		'Snippets Settings',
		'Settings',
		'manage_options',
		'sdr-settings',
		'sdr_render_settings_page'
	);

	add_action('load-' . $settings_page_name, 'sdr_load_settings_page');
}

function sdr_load_settings_page() {
	if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
		add_action( 'admin_enqueue_scripts', 'sdr_admin_enqueue_scripts' );
		return;
	}

	if ( !isset( $_POST['sdr_settings'] ) ) {
		return;
	}

	check_admin_referer( 'sdr_settings', 'sdr_settings' );

	$new_options = sdr_get_options();

	// Color Theme
	if ( isset( $_POST['sdr_ace_theme'] ) ) {
		$new_options['ace_theme'] = sanitize_key( $_POST['sdr_ace_theme'] );
	}

	foreach ($new_options as $option_key => $option_value) {
		update_option( 'sdr_' . $option_key, $option_value );
	}

	wp_redirect( add_query_arg(array()) );
	exit;
}

function sdr_render_settings_page() {
	$options = sdr_get_options();

	include( SDR_PATH . '/templates/settings.php' );
}