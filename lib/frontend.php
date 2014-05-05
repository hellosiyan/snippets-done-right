<?php

function wippets_frontend_init() {
	wippets_add_shortcodes();

	# Add Actions

	add_action( 'wp_enqueue_scripts', 'wippets_enqueue_frontend' );
}

function wippets_add_shortcodes() {
	add_shortcode( 'snippet', 'wippets_do_shortcode_snippet' );
	add_shortcode( 'wippets_snippet', 'wippets_do_shortcode_snippet' );
}

function wippets_do_shortcode_snippet( $atts ) {
	if ( ! isset( $atts['id'] ) ) {
		return;
	}

	$snippet_id = intval( $atts['id'] );
	$snippet = get_post( $snippet_id );

	if ( ! $snippet || $snippet->post_type != 'wippet_snippet' || $snippet->ID != $snippet_id ) {
		return;
	}

	$language = wippets_snippet_get_language( $snippet->ID );

	$html = '';

	$html .= '<div class="wippets-snippet-container" data-language="' . esc_attr( $language ) . '">';
	$html .= '<pre class="wippets-snippet-box">';

	$html .= esc_html( $snippet->post_content );

	$html .= '</pre>';
	$html .= '</div>';

	return $html;
}

function wippets_enqueue_frontend() {
	wp_enqueue_style( 'wippets-style-front', WIPPETS_URL . '/assets/wippets-front.css', array( ), WIPPETS_VERSION );
	wp_enqueue_script( 'wippets-functions-front', WIPPETS_URL . '/assets/wippets-front.js', array( 'jquery' ), WIPPETS_VERSION );
}