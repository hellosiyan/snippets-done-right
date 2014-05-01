<?php
/*
Plugin Name: Wippets
Plugin URI: https://github.com/xsisqox/lime-export
Description: Create, embed, publish and share code snippets
Version: 1.0
Author: Siyan Panayotov
License: GPL2
*/

/*
Copyright (C) 2014 Siyan Panayotov <siyan.panayotov@gmail.com>

This file is part of Wippets.

Wippets is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

Wippets is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Wippets; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Block direct includes
if ( !defined('WPINC') ) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

define('WIPPETS_VERSION', '1.0');

add_action( 'init', 'wippets_init' );

function wippets_init() {
	wippets_load();

	wippets_register_post_types();

	# Add Actions
	add_action('admin_enqueue_scripts', 'wippets_enqueue_admin_scripts');

	add_action('wp_enqueue_scripts', 'wippets_enqueue_ace');
	add_action('admin_enqueue_scripts', 'wippets_enqueue_ace');

	add_action( 'edit_form_after_title', 'wippets_display_code_editor' );

	add_action( 'save_post', 'wippets_snippet_on_save' );
}

function wippets_load() {
	if ( defined('WIPPETS_PATH') ) {
		return;
	}

	define('WIPPETS_PATH', dirname(__FILE__));
	define('WIPPETS_URL', WP_PLUGIN_URL . '/' . basename(WIPPETS_PATH) );

	include_once( WIPPETS_PATH . '/lib/snippets.php' );
	include_once( WIPPETS_PATH . '/lib/ace.php' );
}

function wippets_enqueue_admin_scripts() {
	$screen = get_current_screen();

	if ( $screen->base !== 'post' || $screen->id !== 'wippet_snippet' ) {
		return;
	}

	wp_enqueue_script('wippets-functions-admin', WIPPETS_URL . '/assets/functions.js', array( 'jquery' ), WIPPETS_VERSION);
	wp_enqueue_style('wippets-style-admin', WIPPETS_URL . '/assets/style.css', array( ), WIPPETS_VERSION);
}