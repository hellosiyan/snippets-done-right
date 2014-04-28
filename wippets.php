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
}

function wippets_load() {
	if ( !defined('WP_ADMIN') || defined('WIPPETS_PATH') ) {
		return;
	}

	define('WIPPETS_PATH', dirname(__FILE__));
	define('WIPPETS_URL', WP_PLUGIN_URL . '/' . basename(WIPPETS_PATH) );
}