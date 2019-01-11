<?php

/*
Plugin Name: Easy Date and Time Picker
Plugin URI: https://trendbydesigns.com
Description: Datetime-Picker  is a plugin which appends a pretty nice, multi-language date and time selection popup to a hidden input field using jQuery and moment.js JavaScript libraries.It is easy to use by using short code.
Version: 1.0.0
Author: William Stancil 
Author URI: https://trebdbydesigns.com/about-us
License: GPLv2 or later
Text Domain: Datetime-Picker
*/




function datetime_Picker_js()
{
    // Register the script like this for a plugin:
    wp_register_script( 'pluginsmain-script', plugins_url( '/js/datetimepicker.js', __FILE__ ), array( 'jquery' ) );
    wp_register_script( 'moment-script', plugins_url( '/js/moment.min.js', __FILE__ ), array( 'jquery' ) );
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'pluginsmain-script' );
    wp_enqueue_script( 'moment-script' );
}
add_action( 'wp_enqueue_scripts', 'datetime_Picker_js' );



function datetime_Picker_plugin_main_style() {
	wp_enqueue_style( 'datetime-css', plugins_url( '/css/datetimepicker.css', __FILE__ ));
	wp_enqueue_style( 'fontwsom-css', plugins_url( '/css/font-awesome.min.css', __FILE__ ));
}
add_action('init','datetime_Picker_plugin_main_style');


	





function datetime_Picker_active () {?>
    <script type="text/javascript">
    jQuery(document).ready( function () {
        jQuery('#picker').dateTimePicker();
        jQuery('#picker-no-time').dateTimePicker({ showTime: false, dateFormat: 'DD/MM/YYYY'});
    })
    </script>
<?php
}
add_action('wp_head','datetime_Picker_active');



function datetime_Picker() {
	return'
		<div style="width: 250px; margin: 50px auto;">
			<div id="picker"> </div>
			<input type="hidden" id="result" value="" />
		</div>
	';
}
add_shortcode('datetime', 'datetime_Picker'); 

?>