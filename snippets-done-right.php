<?php
/*
Plugin Name: Snippets Done Right
Plugin URI: http://siyanpanayotov.com/project/snippets-done-right/
Description: Create and embed code snippets with syntax highlighting
Text Domain: snippets-done-right
Version: 1.1
Author: Siyan Panayotov
Author URI: http://siyanpanayotov.com
License: GPL2
*/

/*
Copyright (C) 2014 Siyan Panayotov <contact@siyanpanayotov.com>

This file is part of Snippets Done Right.

Snippets Done Right is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

Snippets Done Right is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Snippets Done Right; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Block direct includes
if ( !defined('WPINC') ) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

define('SDR_VERSION', '1.1');

register_activation_hook( __FILE__, 'sdr_activate' );

add_action( 'init', 'sdr_init' );

function sdr_activate() {
	# Flush rewrite rules

	sdr_load();
	sdr_register_post_types();

    flush_rewrite_rules();
}

function sdr_init() {
	sdr_load();

	sdr_register_post_types();

	# Admin Init

	if ( is_admin() ) {
		sdr_admin_init();
	} else {
		sdr_frontend_init();
	}

	# Add Actions
	
	add_action( 'wp_enqueue_scripts', 'sdr_enqueue_ace' );

	add_action( 'save_post', 'sdr_snippet_on_save' );
}

function sdr_load() {
	if ( defined('SDR_PATH') ) {
		return;
	}

	define('SDR_PATH', dirname(__FILE__));
	define('SDR_URL', WP_PLUGIN_URL . '/' . basename(SDR_PATH) );

	sdr_load_textdomain();

	include_once( SDR_PATH . '/lib/snippets.php' );
	include_once( SDR_PATH . '/lib/ace.php' );
	include_once( SDR_PATH . '/lib/frontend.php' );
	include_once( SDR_PATH . '/lib/admin.php' );
}

function sdr_get_options() {
	$options = array(
		'ace_theme' => 'textmate'
	);

	foreach ($options as $option_name => $default_value) {
		$options[$option_name] = get_option( 'sdr_' . $option_name, $default_value );
	}

	return $options;
}

function sdr_load_textdomain() {
	load_plugin_textdomain( 'snippets-done-right', false, basename(SDR_PATH) . '/languages' );
}
