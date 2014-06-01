<?php

function sdr_frontend_init() {
	sdr_add_shortcodes();

	# Add Actions

	add_action( 'wp_enqueue_scripts', 'sdr_enqueue_frontend' );
}

function sdr_add_shortcodes() {
	add_shortcode( 'snippet', 'sdr_do_shortcode_snippet' );
	add_shortcode( 'sdr_snippet', 'sdr_do_shortcode_snippet' );
}

function sdr_do_shortcode_snippet( $atts ) {
	if ( ! isset( $atts['id'] ) ) {
		return ;
	}

	$snippet_id = intval( $atts['id'] );
	$snippet = get_post( $snippet_id );

	if ( ! $snippet || $snippet->post_type != 'sdr_snippet' || $snippet->ID != $snippet_id ) {
		return;
	}

	$language = sdr_snippet_get_language( $snippet->ID );

	$show_lines = 'true';
	if ( isset( $atts['line_numbers'] ) && $atts['line_numbers'] === 'false' ) {
		$show_lines = 'false';
	}

	$height = 0;
	if ( isset( $atts['height'] ) ) {
		$height = max( 0, intval( $atts['height'] ) );
	}

	$html = '';

	$html .= '<div class="sdr-snippet-container" data-language="' . esc_attr( $language ) . '" data-show-lines="' . esc_attr( $show_lines ) . '" data-height="' . esc_attr( $height ) . '">';
	$html .= '<pre class="sdr-snippet-box">';

	$html .= esc_html( $snippet->post_content );

	$html .= '</pre>';
	$html .= '</div>';

	return $html;
}

function sdr_enqueue_frontend() {
	wp_enqueue_style( 'sdr-style-front', SDR_URL . '/assets/sdr-front.css', null, SDR_VERSION );
	wp_enqueue_script( 'sdr-functions-front', SDR_URL . '/assets/sdr-front.js', array( 'jquery' ), SDR_VERSION, true );
	
	// Pass plugin options to our javascript
	$options = sdr_get_options();
	wp_localize_script( 'sdr-functions-front', 'sdr_options', $options );
}