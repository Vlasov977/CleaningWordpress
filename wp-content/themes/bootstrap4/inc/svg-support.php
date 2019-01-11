<?php

/**
 * ADD MIME TYPES
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'upload_mimes', 'support_for_upload_svg_files' );

function support_for_upload_svg_files( $mimes = array() ) {
	// allow SVG file upload
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';

	return $mimes;
}

/**
 * ADD ABILITY TO VIEW THUMBNAILS IN WP 4.0+
 */

add_action( 'admin_init', 'display_svg_thumbs' );

function display_svg_thumbs() {

	ob_start();

	add_action( 'shutdown', 'svgs_thumbs_filter', 0 );
	function svgs_thumbs_filter() {

		$final     = '';
		$ob_levels = count( ob_get_level() );

		for ( $i = 0; $i < $ob_levels; $i ++ ) {

			$final .= ob_get_clean();

		}

		echo apply_filters( 'final_output', $final );

	}

	add_filter( 'final_output', 'svgs_final_output' );
	function svgs_final_output( $content ) {

		$content = str_replace( '<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>', '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" />
			<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',

			$content );

		$content = str_replace( '<# } else if ( \'image\' === data.type && data.sizes ) { #>', '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" />
				</div>
			<# } else if ( \'image\' === data.type && data.sizes ) { #>',

			$content );

		return $content;

	}
}

/**
 * Insert additional class to img tag and specify img dimensions if user select svg file
 *
 * @param string $html img tag
 *
 * @return string
 */

function additional_img_class( $html ) {
	if ( strpos( $html, '.svg' ) !== false ) {
		$html = preg_replace( '|class="(.+?)"|', 'class="$1 attachment-svg"', $html );
		$html = str_replace( 'width="1"', 'width="64"', $html );
		$html = str_replace( 'height="1"', 'height="64"', $html );
	}

	return $html;
}

add_filter( 'get_image_tag', 'additional_img_class' );