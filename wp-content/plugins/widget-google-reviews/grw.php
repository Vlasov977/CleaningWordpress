<?php
/*
Plugin Name: Google Reviews Widget
Plugin URI: https://richplugins.com/google-reviews-pro-wordpress-plugin
Description: Instantly Google Places Reviews on your website to increase user confidence and SEO.
Author: RichPlugins <support@richplugins.com>
Version: 1.6.7
Author URI: https://richplugins.com
*/

require(ABSPATH . 'wp-includes/version.php');

include_once(dirname(__FILE__) . '/api/urlopen.php');
include_once(dirname(__FILE__) . '/helper/debug.php');

define('GRW_VERSION',             '1.6.7');
define('GRW_GOOGLE_PLACE_API',    'https://maps.googleapis.com/maps/api/place/');
define('GRW_GOOGLE_AVATAR',       'https://lh3.googleusercontent.com/-8hepWJzFXpE/AAAAAAAAAAI/AAAAAAAAAAA/I80WzYfIxCQ/s64-c/114307615494839964028.jpg');
define('GRW_PLUGIN_URL',          plugins_url(basename(plugin_dir_path(__FILE__ )), basename(__FILE__)));

function grw_options() {
    return array(
        'grw_version',
        'grw_active',
        'grw_google_api_key',
        'grw_language',
    );
}

/*-------------------------------- Widget --------------------------------*/
function grw_init_widget() {
    if (!class_exists('Goog_Reviews_Widget' ) ) {
        require 'grw-widget.php';
    }
}
add_action('widgets_init', 'grw_init_widget');

function grw_register_widget() {
    return register_widget("Goog_Reviews_Widget");
}
add_action('widgets_init', 'grw_register_widget');

/*-------------------------------- Menu --------------------------------*/
function grw_setting_menu() {
     add_submenu_page(
         'options-general.php',
         'Google Reviews Widget',
         'Google Reviews Widget',
         'moderate_comments',
         'grw',
         'grw_setting'
     );
}
add_action('admin_menu', 'grw_setting_menu', 10);

function grw_setting() {
    include_once(dirname(__FILE__) . '/grw-setting.php');
}

/*-------------------------------- Links --------------------------------*/
function grw_plugin_action_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=grw') . '">'.grw_i('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'grw_plugin_action_links', 10, 2);

/*-------------------------------- Row Meta --------------------------------*/
function grw_plugin_row_meta($input, $file) {
    if ($file != plugin_basename( __FILE__ )) {
        return $input;
    }

    $links = array(
        '<a href="' . esc_url('https://richplugins.com/documentation') . '" target="_blank">' . grw_i('View Documentation') . '</a>',
        '<a href="' . esc_url('https://richplugins.com/google-reviews-pro-wordpress-plugin') . '" target="_blank">' . grw_i('Upgrade to Pro') . ' &raquo;</a>',
    );
    $input = array_merge($input, $links);
    return $input;
}
add_filter('plugin_row_meta', 'grw_plugin_row_meta', 10, 2);

/*-------------------------------- Database --------------------------------*/
function grw_activation($network_wide) {
    if (grw_does_need_update()) {
        grw_install($network_wide);
    }
}
register_activation_hook(__FILE__, 'grw_activation');

function grw_install($network_wide, $allow_db_install=true) {
    global $wpdb, $userdata;

    $version = (string)get_option('grw_version');
    if (!$version) {
        $version = '0';
    }

    if ($allow_db_install) {
        if (function_exists('is_multisite') && is_multisite() && $network_wide) {
            $current_blog_id = get_current_blog_id();
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                grw_install_db();
            }
            switch_to_blog($current_blog_id);
        } else {
            grw_install_db();
        }
    }

    if (version_compare($version, GRW_VERSION, '=')) {
        return;
    }

    add_option('grw_active', '1');
    add_option('grw_google_api_key', '');
    update_option('grw_version', GRW_VERSION);
}

function grw_install_db() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "grp_google_place (".
           "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
           "place_id VARCHAR(80) NOT NULL,".
           "name VARCHAR(255) NOT NULL,".
           "photo VARCHAR(255),".
           "icon VARCHAR(255),".
           "address VARCHAR(255),".
           "rating DOUBLE PRECISION,".
           "url VARCHAR(255),".
           "website VARCHAR(255),".
           "updated BIGINT(20),".
           "PRIMARY KEY (`id`),".
           "UNIQUE INDEX grp_place_id (`place_id`)".
           ") " . $charset_collate . ";";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);

    $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "grp_google_review (".
           "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
           "google_place_id BIGINT(20) UNSIGNED NOT NULL,".
           "hash VARCHAR(40) NOT NULL,".
           "rating INTEGER NOT NULL,".
           "text VARCHAR(10000),".
           "time INTEGER NOT NULL,".
           "language VARCHAR(10),".
           "author_name VARCHAR(255),".
           "author_url VARCHAR(255),".
           "profile_photo_url VARCHAR(255),".
           "PRIMARY KEY (`id`),".
           "UNIQUE INDEX grp_google_review_hash (`hash`),".
           "INDEX grp_google_place_id (`google_place_id`)".
           ") " . $charset_collate . ";";

    dbDelta($sql);
}

function grw_reset($reset_db) {
    global $wpdb;

    if (function_exists('is_multisite') && is_multisite()) {
        $current_blog_id = get_current_blog_id();
        $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            grw_reset_data($reset_db);
        }
        switch_to_blog($current_blog_id);
    } else {
        grw_reset_data($reset_db);
    }
}

function grw_reset_data($reset_db) {
    global $wpdb;

    foreach (grw_options() as $opt) {
        delete_option($opt);
    }
    if ($reset_db) {
        $wpdb->query("DROP TABLE " . $wpdb->prefix . "grp_google_place;");
        $wpdb->query("DROP TABLE " . $wpdb->prefix . "grp_google_review;");
    }
}

/*-------------------------------- Request --------------------------------*/
function grw_request_handler() {
    global $wpdb;

    if (!empty($_GET['cf_action'])) {

        switch ($_GET['cf_action']) {
            case 'grw_google_api_key':
                if (current_user_can('manage_options')) {
                    if (isset($_POST['grw_wpnonce']) === false) {
                        $error = grw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                        update_option('grw_google_api_key', trim(sanitize_text_field($_POST['key'])));
                        $status = 'success';
                        $response = compact('status');

                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
            break;
            case 'grw_search':
                if (current_user_can('manage_options')) {
                    if (isset($_GET['grw_wpnonce']) === false) {
                        $error = grw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                        $grw_google_api_key = get_option('grw_google_api_key');
                        $url = GRW_GOOGLE_PLACE_API . 'textsearch/json?query=' . $_GET['query'] . '&key=' . $grw_google_api_key;

                        $grw_language = get_option('grw_language');
                        if (strlen($grw_language) > 0) {
                            $url = $url . '&language=' . $grw_language;
                        }

                        $response = rplg_urlopen($url);

                        $response_data = $response['data'];
                        $response_json = rplg_json_decode($response_data);
                        $response_results = $response_json->results;

                        foreach ($response_results as $result) {
                            $result->business_photo = grw_business_avatar($result);
                        }
                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response_results);
                    die();
                }
            break;
            case 'grw_reviews':
                if (current_user_can('manage_options')) {
                    if (isset($_GET['grw_wpnonce']) === false) {
                        $error = grw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                        $url = grw_api_url($_GET['placeid']);

                        $response = rplg_urlopen($url);

                        $response_data = $response['data'];
                        $response_json = rplg_json_decode($response_data);
                        $response_result = $response_json->result;

                        if (isset($response_result)) {
                            $response_result->business_photo = grw_business_avatar($response_result);
                        }
                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response_json->result);
                    die();
                }
            break;
            case 'grw_save':
                if (current_user_can('manage_options')) {
                    if (isset($_POST['grw_wpnonce']) === false) {
                        $error = grw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                        $url = grw_api_url($_POST['placeid']);

                        $response = rplg_urlopen($url);

                        $response_data = $response['data'];
                        $response_json = rplg_json_decode($response_data);

                        if ($response_json && isset($response_json->result)) {
                            $response_json->result->business_photo = grw_business_avatar($response_json->result);
                            grw_save_reviews($response_json->result);
                            $result = $response_json->result;
                            $status = 'success';
                        } else {
                            $result = $response_json;
                            $status = 'failed';
                        }
                        $response = compact('status', 'result');
                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
            break;
        }
    }
}
add_action('init', 'grw_request_handler');

function grw_save_reviews($place, $min_filter = 0) {
    global $wpdb;

    $google_place_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "grp_google_place WHERE place_id = %s", $place->place_id));
    if ($google_place_id) {
        $wpdb->update($wpdb->prefix . 'grp_google_place', array(
            'name'     => $place->name,
            'photo'    => $place->business_photo,
            'rating'   => $place->rating
        ), array('ID'  => $google_place_id));
    } else {
        $wpdb->insert($wpdb->prefix . 'grp_google_place', array(
            'place_id' => $place->place_id,
            'name'     => $place->name,
            'photo'    => $place->business_photo,
            'icon'     => $place->icon,
            'address'  => $place->formatted_address,
            'rating'   => isset($place->rating) ? $place->rating : null,
            'url'      => isset($place->url) ? $place->url : null,
            'website'  => isset($place->website) ? $place->website : null
        ));
        $google_place_id = $wpdb->insert_id;
    }

    if ($place->reviews) {
        $reviews = $place->reviews;
        foreach ($reviews as $review) {
            if ($min_filter > 0 && $min_filter > $review->rating) {
                continue;
            }

            $google_review_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "grp_google_review WHERE time = %s", $review->time));
            if ($google_review_id) {
                $update_params = array(
                    'rating' => $review->rating,
                    'text'   => $review->text
                );
                if (isset($review->profile_photo_url)) {
                    $update_params['profile_photo_url'] = $review->profile_photo_url;
                }
                $wpdb->update($wpdb->prefix . 'grp_google_review', $update_params, array('id' => $google_review_id));
            } else {
                $wpdb->insert($wpdb->prefix . 'grp_google_review', array(
                    'google_place_id'   => $google_place_id,
                    'hash'              => $review->time, //TODO: workaround to support old versions
                    'rating'            => $review->rating,
                    'text'              => $review->text,
                    'time'              => $review->time,
                    'language'          => $review->language,
                    'author_name'       => $review->author_name,
                    'author_url'        => isset($review->author_url) ? $review->author_url : null,
                    'profile_photo_url' => isset($review->profile_photo_url) ? $review->profile_photo_url : null
                ));
            }
        }
    }
}

function grw_lang_init() {
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain('grw', false, basename( dirname( __FILE__ ) ) . '/languages');
}
add_action('plugins_loaded', 'grw_lang_init');

/*-------------------------------- Helpers --------------------------------*/
function grw_enabled() {
    global $id, $post;

    $active = get_option('grw_active');
    if (empty($active) || $active === '0') { return false; }
    return true;
}

function grw_api_url($placeid, $reviews_lang = '') {
    $url = GRW_GOOGLE_PLACE_API . 'details/json?placeid=' . $placeid . '&key=' . get_option('grw_google_api_key');

    $grw_language = strlen($reviews_lang) > 0 ? $reviews_lang : get_option('grw_language');
    if (strlen($grw_language) > 0) {
        $url = $url . '&language=' . $grw_language;
    }
    return $url;
}

function grw_business_avatar($response_result_json) {
    if (isset($response_result_json->photos)) {
        $request_url = add_query_arg(
            array(
                'photoreference' => $response_result_json->photos[0]->photo_reference,
                'key'            => get_option('grw_google_api_key'),
                'maxwidth'       => '300',
                'maxheight'      => '300',
            ),
            'https://maps.googleapis.com/maps/api/place/photo'
        );
        $response = rplg_urlopen($request_url);
        foreach ($response['headers'] as $header) {
            if (strpos($header, 'Location: ') !== false) {
                return str_replace('Location: ', '', $header);
            }
        }
    }
    return null;
}

function grw_does_need_update() {
    $version = (string)get_option('grw_version');
    if (empty($version)) {
        $version = '0';
    }
    if (version_compare($version, '1.0', '<')) {
        return true;
    }
    return false;
}

function grw_i($text, $params=null) {
    if (!is_array($params)) {
        $params = func_get_args();
        $params = array_slice($params, 1);
    }
    return vsprintf(__($text, 'grw'), $params);
}

if (!function_exists('esc_html')) {
function esc_html( $text ) {
    $safe_text = wp_check_invalid_utf8( $text );
    $safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
    return apply_filters( 'esc_html', $safe_text, $text );
}
}

if (!function_exists('esc_attr')) {
function esc_attr( $text ) {
    $safe_text = wp_check_invalid_utf8( $text );
    $safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
    return apply_filters( 'attribute_escape', $safe_text, $text );
}
}

/**
 * JSON ENCODE for PHP < 5.2.0
 */
if (!function_exists('json_encode')) {

    function json_encode($data) {
        return cfjson_encode($data);
    }

    function cfjson_encode_string($str) {
        if(is_bool($str)) {
            return $str ? 'true' : 'false';
        }

        return str_replace(
            array(
                '\\'
                , '"'
                //, '/'
                , "\n"
                , "\r"
            )
            , array(
                '\\\\'
                , '\"'
                //, '\/'
                , '\n'
                , '\r'
            )
            , $str
        );
    }

    function cfjson_encode($arr) {
        $json_str = '';
        if (is_array($arr)) {
            $pure_array = true;
            $array_length = count($arr);
            for ( $i = 0; $i < $array_length ; $i++) {
                if (!isset($arr[$i])) {
                    $pure_array = false;
                    break;
                }
            }
            if ($pure_array) {
                $json_str = '[';
                $temp = array();
                for ($i=0; $i < $array_length; $i++) {
                    $temp[] = sprintf("%s", cfjson_encode($arr[$i]));
                }
                $json_str .= implode(',', $temp);
                $json_str .="]";
            }
            else {
                $json_str = '{';
                $temp = array();
                foreach ($arr as $key => $value) {
                    $temp[] = sprintf("\"%s\":%s", $key, cfjson_encode($value));
                }
                $json_str .= implode(',', $temp);
                $json_str .= '}';
            }
        }
        else if (is_object($arr)) {
            $json_str = '{';
            $temp = array();
            foreach ($arr as $k => $v) {
                $temp[] = '"'.$k.'":'.cfjson_encode($v);
            }
            $json_str .= implode(',', $temp);
            $json_str .= '}';
        }
        else if (is_string($arr)) {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        else if (is_numeric($arr)) {
            $json_str = $arr;
        }
        else if (is_bool($arr)) {
            $json_str = $arr ? 'true' : 'false';
        }
        else {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        return $json_str;
    }
}
?>