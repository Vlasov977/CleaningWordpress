<?php
/*
Plugin Name: Userback
Plugin URI: https://www.userback.io
Description: Userback Wordpress Plugin
Version: 1.01
Author: Lee Le @ Userback
Author URI: https://www.userback.io
*/

    define('PLUGIN_DIR_USERBACK',  'userback');
    define('ADMIN_DIR_USERBACK',   'admin');

    register_activation_hook( __FILE__, 'userback_install' );

    add_action('admin_menu',            'add_menu_userback');
    add_action('admin_enqueue_scripts', 'include_admin_script_userback');
    add_action('wp_ajax_get_userback',  'get_json_userback');
    add_action('wp_ajax_save_userback', 'save_userback');

    add_action('wp_footer',  'add_userback_plugin_html');

    function add_userback_plugin_html() {
        if (is_userback_active()) {
            $settings = get_array_userback();

            print '
<!-- Userback -->
' . $settings['widget_code'] . '
<!-- END -->
';
        }
    }

    // is the widget turned on?
    function is_userback_active() {
        $settings = get_array_userback();

        $is_active = false;

        if (isset($settings['is_active']) && $settings['is_active'] == 1) {
            if (in_array(0, $settings['page']) !== false) {
                $is_active = true;
            } else {
                $post = get_queried_object();
                if ($post && $post->ID) {
                    if (in_array($post->ID, $settings['page']) !== false) {
                        $is_active = true;
                    } else if (in_array(-1, $settings['page']) !== false && ($post->post_status == 'draft' || $post->post_status == 'pending')) {
                        $is_active = true;
                    }
                }
            }
        }

        return $is_active;
    }

    function userback_install() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'userback';

        $sql = 'CREATE TABLE ' . $table_name . ' (
            id                      INT(10)         NOT NULL AUTO_INCREMENT,
            t_is_active             TINYINT(1)      NOT NULL,
            t_page                  TEXT            NOT NULL,
            t_widget_code           TEXT            NOT NULL,
            UNIQUE KEY id (id)
        );';

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


    function add_menu_userback() {
        add_menu_page('Userback', 'Userback', 'manage_options', 'userback', 'print_overview_userback', plugins_url(PLUGIN_DIR_USERBACK . '/logo.png'));
    }

    // include JS / CSS files
    function include_admin_script_userback($hook) {
        wp_enqueue_script('userback-admin-js',  plugins_url(PLUGIN_DIR_USERBACK . '/javascript/admin.js'));
        wp_enqueue_style('userback-admin-css',  plugins_url(PLUGIN_DIR_USERBACK . '/css/admin.css'));
    }

    function print_overview_userback() {
        require_once(ADMIN_DIR_USERBACK . '/overview.php');
    }

    // old WP versions
    if (!function_exists('wp_send_json')) {
        function wp_send_json($response) {
            @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
            echo json_encode( $response );
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
                wp_die();
            else
                die;
        }
    }

    function get_json_userback() {
        $response = get_array_userback();
        print wp_send_json(array(
            'data' => $response,
            'page' => get_pages(array(
                'post_status' => 'publish,inherit,pending,private,future,draft'
            ))
        ));
    }

    function get_array_userback() {
        global $wpdb; // this is how you get access to the database

        $response = array();
        $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'userback LIMIT 0, 1');

        foreach ($rows as $userback) {
            $response = array(
                'page'            => $userback->t_page ? explode(',', $userback->t_page) : array(0),
                'is_active'       => (int)$userback->t_is_active,
                'widget_code'     => $userback->t_widget_code
            );
        };

        return $response;
    }

    function build_post_data_userback() {
        $data = $_POST['data'];

        $userback_data = array(
            'page'            => (isset($data['page'])                   ? implode(',', $data['page']) : '0'),
            'is_active'       => (isset($data['is_active'])              ? $data['is_active']          : 0),
            'widget_code'     => (isset($data['widget_code'])            ? $data['widget_code']        : '')
        );

        return $userback_data;
    }

    function save_userback() {
        global $wpdb;

        _magic_quote_fix();

        if (isset($_POST['data'])) {
            $data = build_post_data_userback();

            $sql = 'DELETE FROM ' . $wpdb->prefix . 'userback';
            $wpdb->get_results($sql);

            $sql = 'INSERT INTO ' . $wpdb->prefix . 'userback (`t_page`, `t_is_active`, `t_widget_code`) VALUES ('.
                '"' . esc_sql($data['page']) . '", ' .
                '"' . esc_sql($data['is_active']) . '", '.
                '"' . esc_sql($data['widget_code']) . '"' .
            ');';

            $wpdb->get_results($sql);

            print wp_send_json(true);
        }

        print wp_send_json(false);
    }

    if (!function_exists('_magic_quote_fix')) {
        function _magic_quote_fix() {
            $_POST      = array_map('stripslashes_deep', $_POST);
            $_GET       = array_map('stripslashes_deep', $_GET);
            $_COOKIE    = array_map('stripslashes_deep', $_COOKIE);
            $_REQUEST   = array_map('stripslashes_deep', $_REQUEST);
        }
    }
?>