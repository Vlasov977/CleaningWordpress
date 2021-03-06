<?php
/**
 * Functions
 */

/******************************************************************************
 * Included Functions
 ******************************************************************************/

// Helpers function
require_once get_stylesheet_directory() . '/inc/helpers.php';
// Install Recommended plugins
require_once get_stylesheet_directory() . '/inc/recommended-plugins.php';
// Walker modification
require_once get_stylesheet_directory() . '/inc/class-bootstrap-navigation.php';
// Home slider function
include_once get_stylesheet_directory() . '/inc/home-slider.php';
// Dynamic admin
include_once get_stylesheet_directory() . '/inc/class-dynamic-admin.php';
// SVG Support
include_once get_stylesheet_directory() . '/inc/svg-support.php';
// Extend WP Search with Custom fields
include_once get_stylesheet_directory() . '/inc/custom-fields-search.php';
// Include all additional shortcodes
//include_once get_stylesheet_directory() . '/inc/shortcodes.php';
// Constants
define( 'IMAGE_PLACEHOLDER', get_stylesheet_directory_uri() . '/images/placeholder.jpg' );


/******************************************************************************
 * Global Functions
 ******************************************************************************/

// By adding theme support, we declare that this theme does not use a
// hard-coded <title> tag in the document head, and expect WordPress to
// provide it for us.
add_theme_support( 'title-tag' );

//  Add widget support shortcodes
add_filter( 'widget_text', 'do_shortcode' );

// Support for Featured Images
add_theme_support( 'post-thumbnails' );

// Custom Background
add_theme_support( 'custom-background', array( 'default-color' => 'fff' ) );

// Custom Header
add_theme_support( 'custom-header', array(
	'default-image' => get_template_directory_uri() . '/images/custom-logo.png',
	'height'        => '200',
	'flex-height'   => true,
	'uploads'       => true,
	'header-text'   => false
) );

// Custom Logo
add_theme_support( 'custom-logo', array(
	'height'      => '150',
	'flex-height' => true,
	'flex-width'  => true,
) );

function show_custom_logo( $size = 'medium' ) {
	if ( $custom_logo_id = get_theme_mod( 'custom_logo' ) ) {
		$attachment_array = wp_get_attachment_image_src( $custom_logo_id, $size );
		$logo_url         = $attachment_array[0];
	} else {
		$logo_url = get_stylesheet_directory_uri() . '/images/custom-logo.png';
	}
	$logo_image = '<img src="' . $logo_url . '" class="custom-logo" itemprop="siteLogo" alt="' . get_bloginfo( 'name' ) . '">';
	$html       = sprintf( '<a href="%1$s" class="navbar-brand" rel="home" title="%2$s" itemscope>%3$s</a>', esc_url( home_url( '/' ) ), get_bloginfo( 'name' ), $logo_image );
	echo apply_filters( 'get_custom_logo', $html );
}

// Add HTML5 elements
add_theme_support( 'html5', array(
	'comment-list',
	'search-form',
	'comment-form',
	'gallery',
	'caption'
) );

// Add excerpt to pages
add_post_type_support( 'page', 'excerpt' );

// Register Navigation Menu
register_nav_menus( array(
	'header-menu' => 'Header Menu',
	'footer-menu' => 'Footer Menu'
) );

// Create pagination
function bootstrap_pagination( $query = '' ) {
	if ( empty( $query ) ) {
		global $wp_query;
		$query = $wp_query;
	}
	
	$big = 999999999;
	
	$links = paginate_links( array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'prev_next' => true,
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'total'     => $query->max_num_pages,
			'type'      => 'list'
		) );

	$pagination = str_replace( 'page-numbers', 'pagination', $links );
	
	echo $pagination;
}

// Register Sidebars
function bootstrap_widgets_init() {
	/* Sidebar Right */
	register_sidebar( array(
		'id'            => 'bootstrap_sidebar_right',
		'name'          => __( 'Sidebar Right' ),
		'description'   => __( 'This sidebar is located on the right-hand side of each page.' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>',
	) );
}

add_action( 'widgets_init', 'bootstrap_widgets_init' );

// Remove #more anchor from posts
function remove_more_jump_link( $link ) {
	$offset = strpos( $link, '#more-' );
	if ( $offset ) {
		$end = strpos( $link, '"', $offset );
	}
	if ( $end ) {
		$link = substr_replace( $link, '', $offset, $end - $offset );
	}
	
	return $link;
}

add_filter( 'the_content_more_link', 'remove_more_jump_link' );


// Callback function to insert 'styleselect' into the $buttons array
function my_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );



// Callback function to filter the MCE settings
function my_mce_before_init_insert_formats( $init_array ) {
    // Define the style_formats array
    $style_formats = array(
        // Each array child is a format with it's own settings
        array(
            'title' => 'Custom button',
            'selector' => 'a',
            'classes' => 'custom_button',
            'wrapper' => false,

        ),

    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );

    return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );


function wpdocs_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );


// Add Google fonts to editor
add_action( 'after_setup_theme', 'leaven_editor_fonts' );
function leaven_editor_fonts() {
    $font_url = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Open+Sans:300,400,700|Lora:400italic,700italic' );
    add_editor_style( $font_url );
}


// Create a function that adds the font selector
if (!function_exists('PREFIX_mce_buttons')) {
    function PREFIX_mce_buttons($buttons)
    {
        array_unshift($buttons, 'fontsizeselect'); // Add Font Size Select
        return $buttons;
    }
}

// Hook the function the mce buttons event
add_filter('mce_buttons_2', 'PREFIX_mce_buttons');


// Everything that follows is OTIONAL ---
// The following is only if you want to customize the default font sizes


// Create a function to Customize mce editor font sizes
if (!function_exists('PREFIX_mce_text_sizes')) {
    function PREFIX_mce_text_sizes($initArray)
    {
        $initArray['fontsize_formats'] = "9px 10px 11px 12px 13px 14px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 34px 36px 38px 40px 42px 44px 46px 48px 50px 52px 54px 56px 57px 58px 60px";
        return $initArray;
    }
}
// Hook the function in the mce before init event
add_filter('tiny_mce_before_init', 'PREFIX_mce_text_sizes');
/******************************************************************************************************************************
 * Enqueue Scripts and Styles for Front-End
 *******************************************************************************************************************************/

function bootstrap_scripts_and_styles() {
	if ( ! is_admin() ) {




		// Load Stylesheets
		//core
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', null, '4.0.0' );

		//plugins
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/plugins/fontawesome.min.css', null, '5.3.1' );
		wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/plugins/slick.css', null, '1.8.0' );
		wp_enqueue_style( 'fancybox.v2', get_template_directory_uri() . '/css/plugins/jquery.fancybox.v2.css', null, '2.1.5' );
//		wp_enqueue_style( 'fancybox.v3', get_template_directory_uri() . '/css/plugins/jquery.fancybox.v3.css', null, '3.4.1' );

		//system

		wp_enqueue_style( 'custom', get_template_directory_uri() . '/css/custom.css', null, null );/*3rd priority*/
		wp_enqueue_style( 'media-screens', get_template_directory_uri() . '/css/media-screens.css', null, null );/*2nd priority*/
		wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css', null, null );/*1st priority*/



        ///AOS
        wp_register_style('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css', null);
        wp_enqueue_style('aos');

        wp_enqueue_style( 'magnf_pop',get_template_directory_uri() .'/css/plugins/magnific-popup.css' ,null);







        wp_register_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', false, null);
        wp_enqueue_script('aos');




		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bootstrap.min', get_template_directory_uri() . '/js/bootstrap.min.js', null, '4.0.0', true );


		//plugins
		wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/plugins/slick.min.js', null, '1.6.0', true );
		wp_enqueue_script( 'matchHeight', get_template_directory_uri() . '/js/plugins/jquery.matchHeight-min.js', null, '0.7.0', true );
        wp_enqueue_script( 'true_loadmore', get_stylesheet_directory_uri() . '/js/loadmore.js', array('jquery') );
		wp_enqueue_script( 'fancybox.v2', get_template_directory_uri() . '/js/plugins/jquery.fancybox.v2.js', null, '2.1.5', true );
		//		wp_enqueue_script( 'fancybox.v3', get_template_directory_uri() . '/js/plugins/jquery.fancybox.v3.js', null, '3.4.1', true );
		//		wp_enqueue_script( 'google.maps.api', 'https://maps.googleapis.com/maps/api/js?key=' . (get_theme_mod( 'google_maps_api' ) ?: 'AIzaSyAs19C89zcw7bQ12hJEKgtPGK9Q8iuLkQ4') . '&v=3.exp', null, null, true );
        wp_register_script('magnf_js', get_template_directory_uri() . 'magnific-popup/jquery.magnific-popup.js', false, null);
        wp_register_script('magnf_p',get_template_directory_uri() . '/js/plugins/jquery.magnific-popup.min.js', false, null);

		//custom javascript
		wp_enqueue_script( 'global', get_template_directory_uri() . '/js/global.js', null, null, true ); /* This should go first */
		
	}
}

add_action( 'wp_enqueue_scripts', 'bootstrap_scripts_and_styles' );


/******************************************************************************
 * Additional Functions
 *******************************************************************************/

// Enable revisions for all custom post types
add_filter( 'cptui_user_supports_params', function () {
	return array( 'revisions' );
} );

if ( function_exists( 'cptui_get_post_type_data' ) ) {
	add_filter( 'wp_revisions_to_keep', 'limit_revisions_number', 10, 2 );
	
	function limit_revisions_number( $num, $post ) {
		$custom_post_types = cptui_get_post_type_data();
		
		if ( ! $custom_post_types ) {
			return $num;
		}
		
		foreach ( $custom_post_types as $custom_post_type ) {
			$cpt_names[] = $custom_post_type['name'];
		}
		if ( isset( $cpt_names ) && in_array( $post->post_type, $cpt_names ) ) {
			$num = 15;
		}
		
		return $num;
	}
}

// Register Post Type Slider
function post_type_slider() {
	$post_type_slider_labels = array(
		'name'               => _x( 'Slider', 'post type general name' ),
		'singular_name'      => _x( 'Slide', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'slide' ),
		'add_new_item'       => __( 'Add New' ),
		'edit_item'          => __( 'Edit' ),
		'new_item'           => __( 'New ' ),
		'all_items'          => __( 'All' ),
		'view_item'          => __( 'View' ),
		'search_items'       => __( 'Search for a slide' ),
		'not_found'          => __( 'No slides found' ),
		'not_found_in_trash' => __( 'No slides found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Slider'
	);
	$post_type_slider_args   = array(
		'labels'        => $post_type_slider_labels,
		'description'   => 'Display Slider',
		'public'        => true,
		'menu_icon'     => 'dashicons-format-gallery',
		'menu_position' => 5,
		'supports'      => array(
			'title',
			'thumbnail',
			'page-attributes',
			'editor',
			'post-formats'
		),
		'has_archive'   => true,
		'hierarchical'  => true
	);
	register_post_type( 'slider', $post_type_slider_args );
	add_theme_support( 'post-formats', array( 'video' ) );
}

add_action( 'init', 'post_type_slider' );

add_action( 'add_meta_boxes', 'slide_background_metabox' );
function slide_background_metabox() {
	$screens = array( 'slider' );
	add_meta_box( 'slide_background', 'Slide background', 'slider_background_callback', $screens );
}

function slider_background_callback( $post, $meta ) {
	$screens = $meta['args'];
	
	wp_nonce_field( 'save_video_bg', 'foundation_nonce' );
	
	echo '<p class="label-wrapper"><label for="slide_video" style="display: block;"><b>Video background</b></label></p>';
	echo '<input type="text" id= "slide_video" name="slide_video_bg" value="' . get_post_meta( $post->ID, 'slide_video_bg',true ) . '" style="width: 100%;"/>';
}

/**
 * Update slide background on slide save
 */
add_action( 'save_post', 'save_slide_background' );

function save_slide_background( $post_id ) {

//	var_dump($_POST);

	if ( ! isset( $_POST['slide_video_bg'] ) ) {
		return;
	}
	
	if ( ! wp_verify_nonce( $_POST['foundation_nonce'],'save_video_bg' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	update_post_meta( $post_id, 'slide_video_bg', $_POST['slide_video_bg'] );
	
}

/**
 * Print script to hande appearance of metabox
 */
//add_action('admin_enqueue_scripts','display_metaboxes');
add_action( 'admin_footer', 'display_metaboxes' );

function display_metaboxes() {
	
	if ( get_post_type() == "slider" ) :
		?>
		<script type="text/javascript">// <![CDATA[
			$ = jQuery;

			function displayMetaboxes() {
				$( '#slide_background' ).hide();
				var selectedFormat = $( "input[name='post_format']:checked" ).val();
				if ( selectedFormat == 'video' ) {
					$( '#slide_background' ).show();
				}
			}

			$( function () {
				displayMetaboxes();
				$( "input[name='post_format']" ).change( function () {
					displayMetaboxes();
				} );
			} );
			// ]]></script>
	<?php
	endif;
}

// Enable control over YouTube iframe through API + add unique ID

function add_youtube_iframe_args( $html, $url, $args ) {
	
	/* Modify video parameters. */
	if ( strstr( $html, 'youtube.com/embed/' ) && !empty($args['location']) ) {
		preg_match_all( '|embed/(.*)\?|', $html, $matches );
		$html = str_replace( '?feature=oembed', '?feature=oembed&enablejsapi=1&autoplay=1&mute=1&controls=0&loop=1&showinfo=0&rel=0&playlist=' . $matches[1][0], $html );
		$html = str_replace( '<iframe', '<iframe enablejsapi="1" id=slide-' . get_the_ID(), $html );
	}
	
	return $html;
}

add_filter( 'oembed_result', 'add_youtube_iframe_args', 10, 3 );

// Stick Admin Bar To The Top
if ( ! is_admin() ) {
	add_action( 'get_header', 'remove_topbar_bump' );
	
	function remove_topbar_bump() {
		remove_action( 'wp_head', '_admin_bar_bump_cb' );
	}
	
	function stick_admin_bar() {
		echo "
			<style type='text/css'>
				body.wp-admin-bar {margin-top:32px !important}
				@media screen and (max-width: 768px) {
					body.wp-admin-bar { margin-top:46px !important }
			
				}
			</style>
			";
		if(is_user_logged_in()){
		    echo "<style type='text/css'>
				@media screen and (max-width: 768px) {
					body {
					padding-top: 50px;
					}
					.header {
					top: 46px;
					}
				}
			</style>";
        }
	}
	
	add_action( 'admin_head', 'stick_admin_bar' );
	add_action( 'wp_head', 'stick_admin_bar' );
}

// Customize Login Screen
function wordpress_login_styling() {
	if ( $custom_logo_id = get_theme_mod( 'custom_logo' ) ) {
		$custom_logo_img = wp_get_attachment_image_src( $custom_logo_id, 'medium' );
		$custom_logo_src = $custom_logo_img[0];
	} else {
		$custom_logo_src = 'wp-admin/images/wordpress-logo.svg?ver=20131107';
	}
	?>
	<style type="text/css">
		.login #login h1 a {
			background-image: url('<?php echo $custom_logo_src; ?>');
			background-size: contain;
			background-position: 50% 50%;
			width: auto;
			height: 120px;
		}
		
		body.login {
			background-color: #f1f1f1;
			<?php if ($bg_image = get_background_image()) {?>
			background-image: url('<?php echo $bg_image; ?>') !important;
			<?php } ?>
			background-repeat: repeat;
			background-position: center center;
		}
	</style>
<?php }

add_action( 'login_enqueue_scripts', 'wordpress_login_styling' );

function admin_logo_custom_url() {
	$site_url = get_bloginfo( 'url' );
	
	return ( $site_url );
}

add_filter( 'login_headerurl', 'admin_logo_custom_url' );

/**
 * Display GravityForms fields label if it set to Hidden
 */

function display_gf_fields_label () {
	echo '<style>.hidden_label label.gfield_label{visibility:visible;line-height:inherit;}</style>';
}

add_action('admin_head','display_gf_fields_label');

// ACF Pro Options Page

if ( function_exists( 'acf_add_options_page' ) ) {
	
	acf_add_options_page( array(
		'page_title' => 'Theme General Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );
	
}

// Set Google Map API key

function set_custom_google_api_key() {
	acf_update_setting( 'google_api_key', get_theme_mod( 'google_maps_api' ) ?: 'AIzaSyAs19C89zcw7bQ12hJEKgtPGK9Q8iuLkQ4' );
}

add_action( 'acf/init', 'set_custom_google_api_key' );

// Disable Emoji

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'tiny_mce_plugins', 'disable_wp_emojis_in_tinymce' );
function disable_wp_emojis_in_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// Wrap any iframe and emved tag into div for responsive view

function iframe_wrapper( $content ) {
	// match any iframes
	$pattern = '~<iframe.*?<\/iframe>|<embed.*?<\/embed>~';
	preg_match_all( $pattern, $content, $matches );
	
	foreach ( $matches[0] as $match ) {
		// Check if it is a video player iframe
		if ( strpos( $match, 'youtu' ) || strpos( $match, 'vimeo' ) ) {
			// wrap matched iframe with div
			$wrappedframe = '<div class="responsive-embed widescreen">' . $match . '</div>';
			//replace original iframe with new in content
			$content = str_replace( $match, $wrappedframe, $content );
		}
	}
	
	return $content;
}

add_filter( 'the_content', 'iframe_wrapper' );


// Dynamic Admin
if ( is_admin() ) {
	// $dynamic_admin = new DynamicAdmin();
	//	$dynamic_admin->addField( 'page', 'template', 'Page Template', 'template_detail_field_for_page' );
	
	// $dynamic_admin->run();
}

// Register Google Maps API key settings in customizer

function register_google_maps_settings( $wp_customize ) {
	$wp_customize->add_section( 'google_maps', array(
		'title'    => __( 'Google Maps', 'foundation' ),
		'priority' => 30,
	) );
	$wp_customize->add_setting( 'google_maps_api', array(
		'default' => 'AIzaSyAs19C89zcw7bQ12hJEKgtPGK9Q8iuLkQ4',
	) );
	
	$wp_customize->add_control( 'google_maps_api', array(
		'label'    => __( 'Google Maps API key', 'foundation' ),
		'section'  => 'google_maps',
		'settings' => 'google_maps_api',
		'type'     => 'text',
	) );
}

add_action( 'customize_register', 'register_google_maps_settings' );

// Enable GF Honeypot for all forms

add_action( 'gform_after_save_form', 'enable_honeypot_on_new_form_creation', 10, 2 );

function enable_honeypot_on_new_form_creation( $form, $is_new ) {
	if ( $is_new ) {
		$form['enableHoneypot'] = true;
		$form['is_active']      = 1;
		GFAPI::update_form( $form );
	}
}

/**
 * Custom styles in TinyMCE
 */

function custom_style_selector( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	
	return $buttons;
}

add_filter( 'mce_buttons_2', 'custom_style_selector' );

function insert_custom_formats( $init_array ) {
	// Define the style_formats array
	$style_formats               = array(
		array(
			'title'    => 'Heading 1',
			'classes'  => 'h1',
			'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
			'wrapper'  => false,
		),
		array(
			'title'    => 'Heading 2',
			'classes'  => 'h2',
			'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
			'wrapper'  => false,
		),
		array(
			'title'    => 'Heading 3',
			'classes'  => 'h3',
			'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
			'wrapper'  => false,
		),
		array(
			'title'    => 'Heading 4',
			'classes'  => 'h4',
			'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
			'wrapper'  => false,
		),
		array(
			'title'    => 'Heading 5',
			'classes'  => 'h5',
			'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
			'wrapper'  => false,
		),
		array(
			'title'    => 'Heading 6',
			'classes'  => 'h6',
			'selector' => 'h1,h2,h3,h4,h5,h6,p,li',
			'wrapper'  => false,
		),
		
		array(
			'title'    => 'Button',
			'classes'  => 'button',
			'selector' => 'a',
			'wrapper'  => false,
		),
	);
	$init_array['style_formats'] = json_encode( $style_formats );
	
	return $init_array;
	
}

add_filter( 'tiny_mce_before_init', 'insert_custom_formats' );

add_editor_style();


/*********************** PUT YOU FUNCTIONS BELOW ********************************/

add_image_size( 'full_hd', 1920, 1080, array( 'center', 'center' ) );
// add_image_size( 'name', width, height, array('center','center'));




function true_load_posts(){

    $args = unserialize( stripslashes( $_POST['query'] ) );
    $args['paged'] = $_POST['page'] + 1; // следующая страница
    $args['post_status'] = 'publish';
    $args[ 'post_type'] = 'our_results_cpt';
    $args[ 'posts_per_page'] = 6;

    // обычно лучше использовать WP_Query, но не здесь
    query_posts( $args );
    // если посты есть
    if( have_posts() ) :

        // запускаем цикл
        while( have_posts() ): the_post();?>

            <div class="col-lg-4  col-md-4  col-sm-12">
                <?php $mainImg = get_the_post_thumbnail_url(); ?>
                <div class="result d-flex js--square" <?php bg($mainImg) ?>>
<!--                    --><?php //if (have_rows('slider')): ?>
<!--                        <div class="modal_window">-->
<!--                            <div class="modal_window__slider">-->
<!--                                --><?php //while (have_rows('slider')) : the_row(); ?>
<!---->
<!--                                    <div class="slide">-->
<!--                                        --><?php //if ($slide = get_sub_field('slide')): ?>
<!--                                            <img src="--><?php //echo $slide['url']; ?><!--" alt="">-->
<!--                                        --><?php //endif; ?>
<!--                                    </div>-->
<!---->
<!--                                --><?php //endwhile; ?>
<!---->
<!--                            </div>-->
<!--                        </div>-->
<!--                    --><?php //endif; ?>
                </div>
            </div>

        <?endwhile;

    endif;
    die();
}


add_action('wp_ajax_loadmore', 'true_load_posts');
add_action('wp_ajax_nopriv_loadmore', 'true_load_posts');




/*******************************************************************************/


/******************* HIDE/SHOW WORDPRESS PLUGINS MENU ITEM *********************/

/**
 * Remove and Restore ability to Add new plugins to site
 */

function remove_plugins_menu_item( $role_name ) {
	$role = get_role( $role_name );
	$role->remove_cap( 'activate_plugins' );
	$role->remove_cap( 'install_plugins' );
	$role->remove_cap( 'upload_plugins' );
	$role->remove_cap( 'update_plugins' );
}

function restore_plugins_menu_item( $role_name ) {
	$role = get_role( $role_name );
	$role->add_cap( 'activate_plugins' );
	$role->add_cap( 'install_plugins' );
	$role->add_cap( 'upload_plugins' );
	$role->add_cap( 'update_plugins' );
}

// remove_plugins_menu_item('administrator');
// restore_plugins_menu_item('administrator');


/*******************************************************************************/