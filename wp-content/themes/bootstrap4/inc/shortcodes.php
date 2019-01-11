<?php

/**
 * Example shortcode
 * [example_shortcode foo=bar]
 *
 * @param $atts array Shortcode attributes
 *
 * @return string
 */

function example_shortcode_callback( $atts ) {
	// Set white list of attributes and specify its default values
	$atts = shortcode_atts( array(
		'foo' => 'no foo',
	), $atts, 'example_shortcode' );
	
	$html = 'foo:' . $atts['foo'];
	
	return $html;
}
add_shortcode( 'example_shortcode', 'example_shortcode_callback' );