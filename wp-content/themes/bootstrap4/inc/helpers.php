<?php
/**
 * Output HTML markup of template with passed args
 *
 * @param string $file File name without extension (.php)
 * @param array $args Array with args ($key=>$value)
 * @param string $default_folder Requested file folder
 *
 * @author DimonPDAA, SemAlley
 * */
function show_template( $file, $args = null, $default_folder = 'parts' ) {
	echo return_template( $file, $args, $default_folder );
}

/**
 * Return HTML markup of template with passed args
 *
 * @param string $file File name without extension (.php)
 * @param array $args Array with args ($key=>$value)
 * @param string $default_folder Requested file folder
 *
 * @return string template HTML
 * @author DimonPDAA
 * */
function return_template( $file, $args = null, $default_folder = 'parts' ) {
	$file = $default_folder . '/' . $file . '.php';
	if ( $args ) {
		extract( $args );
	}
	if ( locate_template( $file ) ) {
		ob_start();
		include( locate_template( $file ) ); //Theme Check free. Child themes support.
		$template_content = ob_get_clean();

		return $template_content;
	}

	return '';
}

/**
 * Get Post Featured image
 *
 * @var int $id Post id
 * @var string $size = 'full' featured image size
 *
 * @return string Post featured image url
 * @author DimonPDAA
 */
function get_attached_img_url( $id = 0, $size = "medium_large" ) {
	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );

	return $img[0];
}

/**
 * Dynamic admin function
 *
 * @var string $column_name Column id
 * @var int $post_id Post id
 *
 * @return void
 * @author DimonPDAA
 */
function template_detail_field_for_page( $column_name, $post_id ) {
	if ( $column_name == 'template' ) {
		$template_name = str_replace( '.php', '', get_post_meta( $post_id, '_wp_page_template', true ) );
		echo '<span style="text-transform: capitalize;">' . str_replace( array(
				'template-', '/'
			), '', substr( $template_name, strpos( $template_name, '/' ), strlen( $template_name ) ) ) . ' Page</span>';
	}

	return;
}

/**
 * Output background image style
 *
 * @param array|string $img Image array or url
 * @param string $size Image size to retrieve
 * @param bool $echo Whether to output the the style tag or return it.
 *
 * @return string|void String when retrieving.
 * @author DimonPDAA
 */
function bg( $img, $size = '', $echo = true ) {
	
	if ( ! $img ) {
		return;
	}
	
	if ( is_array( $img ) ) {
		$url = $size ? $img['sizes'][ $size ] : $img['url'];
	} else {
		$url = $img;
	}
	
	$string = 'style="background-image: url(' . $url . ')"';
	
	if ( $echo ) {
		echo $string;
	} else {
		return $string;
	}
}

/**
 * Format phone number, trim all unnecessary characters
 *
 * @param string $phone Phone number
 *
 * @return string Formatted phone number
 */
function preparePhone( $phone ) {
	return preg_replace( '/[^+\d]+/', '', $phone );
}

/**
 * Return/Output SVG as html
 *
 * @param array|string $img Image link or array
 * @param string $class Additional class attribute for img tag
 * @param string $size Image size if $img is array
 *
 * @return void
 */
function display_svg( $img, $class = '', $size = 'medium' ) {
	echo return_svg( $img, $class, $size );
}

function return_svg( $img, $class = '', $size = 'medium' ) {
	if ( ! $img ) {
		return '';
	}

	$icon_url = is_array( $img ) ? $img['url'] : $img;

	$file_info = pathinfo( $icon_url );
	if ( $file_info['extension'] == 'svg' ):
		$arrContextOptions = array(
			"ssl" => array(
				"verify_peer" => false, "verify_peer_name" => false,
			),
		);
		$image = file_get_contents( $icon_url, false, stream_context_create( $arrContextOptions ) );
	elseif ( is_array( $img ) ):
		$image = '<img class="' . $class . '" src="' . $img['sizes'][ $size ] . '" />';
	else :
		$image = '<img class="' . $class . '" src="' . $img . '" />';
	endif;

	return $image;
}