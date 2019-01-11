<?php

if (!current_user_can('manage_options')) {
    die('The account you\'re logged in to doesn\'t have permission to access this page.');
}

function grw_has_valid_nonce() {
    $nonce_actions = array('grw_reset', 'grw_settings', 'grw_active');
    $nonce_form_prefix = 'grw-form_nonce_';
    $nonce_action_prefix = 'grw-wpnonce_';
    foreach ($nonce_actions as $key => $value) {
        if (isset($_POST[$nonce_form_prefix.$value])) {
            check_admin_referer($nonce_action_prefix.$value, $nonce_form_prefix.$value);
            return true;
        }
    }
    return false;
}

function grw_debug() {
    global $wpdb;
    $places = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "grp_google_place");
    $places_error = $wpdb->last_error;
    $reviews = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "grp_google_review");
    $reviews_error = $wpdb->last_error; ?>

DB Places: <?php echo print_r($places); ?>

DB Places error: <?php echo $places_error; ?>

DB Reviews: <?php echo print_r($reviews); ?>

DB Reviews error: <?php echo $reviews_error;
}

if (!empty($_POST)) {
    $nonce_result_check = grw_has_valid_nonce();
    if ($nonce_result_check === false) {
        die('Unable to save changes. Make sure you are accessing this page from the Wordpress dashboard.');
    }
}

// Reset
if (isset($_POST['reset'])) {
    grw_reset(isset($_POST['reset_db']));
    unset($_POST);
?>
<div class="wrap">
    <h3><?php echo grw_i('Google Reviews Widget Reset'); ?></h3>
    <form method="POST" action="?page=grw">
        <?php wp_nonce_field('grw-wpnonce_grw_reset', 'grw-form_nonce_grw_reset'); ?>
        <p><?php echo grw_i('Google Reviews Widget has been reset successfully.') ?></p>
        <ul style="list-style: circle;padding-left:20px;">
            <li><?php echo grw_i('Local settings for the plugin were removed.') ?></li>
        </ul>
        <p>
            <?php echo grw_i('If you wish to reinstall, you can do that now.') ?>
            <a href="?page=grw">&nbsp;<?php echo grw_i('Reinstall') ?></a>
        </p>
    </form>
</div>
<?php
die();
}

// Post fields that require verification.
$valid_fields = array(
    'grw_active' => array(
        'key_name' => 'grw_active',
        'values' => array('Disable', 'Enable')
    ));

// Check POST fields and remove bad input.
foreach ($valid_fields as $key) {

    if (isset($_POST[$key['key_name']]) ) {

        // SANITIZE first
        $_POST[$key['key_name']] = trim(sanitize_text_field($_POST[$key['key_name']]));

        // Validate
        if (isset($key['regexp']) && $key['regexp']) {
            if (!preg_match($key['regexp'], $_POST[$key['key_name']])) {
                unset($_POST[$key['key_name']]);
            }

        } else if (isset($key['type']) && $key['type'] == 'int') {
            if (!intval($_POST[$key['key_name']])) {
                unset($_POST[$key['key_name']]);
            }

        } else {
            $valid = false;
            $vals = $key['values'];
            foreach ($vals as $val) {
                if ($_POST[$key['key_name']] == $val) {
                    $valid = true;
                }
            }
            if (!$valid) {
                unset($_POST[$key['key_name']]);
            }
        }
    }
}

if (isset($_POST['grw_active']) && isset($_GET['grw_active'])) {
    update_option('grw_active', ($_GET['grw_active'] == '1' ? '1' : '0'));
}

if (isset($_POST['grw_setting'])) {
    update_option('grw_language', $_POST['grw_language']);
    update_option('grw_google_api_key', $_POST['grw_google_api_key']);

    /*$grw_google_api_key = $_POST['grw_google_api_key'];
    if (strlen($grw_google_api_key) > 0) {
        $test_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ3TH9CwFZwokRIvNO1SP0WLg&key=" . $grw_google_api_key;
        $test_response = rplg_urlopen($test_url);
        $test_response_data = $test_response['data'];
        $test_response_json = rplg_json_decode($test_response_data);
        if (isset($test_response_json->error_message) && strlen($test_response_json->error_message) > 0) {
            $grw_google_api_key_error = $test_response_json->error_message;
        }
        update_option('grw_google_api_key', $grw_google_api_key);
    }*/
    $grw_setting_page = true;
} else {
    $grw_setting_page = false;
}

if (isset($_POST['grw_install_db'])) {
    grw_install_db();
}

wp_register_style('twitter_bootstrap3_css', plugins_url('/static/css/bootstrap.min.css', __FILE__));
wp_enqueue_style('twitter_bootstrap3_css', plugins_url('/static/css/bootstrap.min.css', __FILE__));

wp_register_style('rplg_setting_css', plugins_url('/static/css/rplg-setting.css', __FILE__));
wp_enqueue_style('rplg_setting_css', plugins_url('/static/css/rplg-setting.css', __FILE__));

wp_enqueue_script('jquery');

$grw_enabled = get_option('grw_active') == '1';
$grw_google_api_key = get_option('grw_google_api_key');
$grw_language = get_option('grw_language');
?>

<span class="rplg-version"><?php echo grw_i('Free Version: %s', esc_html(GRW_VERSION)); ?></span>
<div class="rplg-setting container-fluid">
    <img src="<?php echo GRW_PLUGIN_URL . '/static/img/google.png'; ?>" alt="Google">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"<?php if (!$grw_setting_page) { ?> class="active"<?php } ?>>
            <a href="#about" aria-controls="about" role="tab" data-toggle="tab"><?php echo grw_i('About'); ?></a>
        </li>
        <li role="presentation"<?php if ($grw_setting_page) { ?> class="active"<?php } ?>>
            <a href="#setting" aria-controls="setting" role="tab" data-toggle="tab"><?php echo grw_i('Setting'); ?></a>
        </li>
        <li role="presentation">
            <a href="#shortcode" aria-controls="shortcode" role="tab" data-toggle="tab"><?php echo grw_i('Shortcode Builder'); ?></a>
        </li>
        <li role="presentation">
            <a href="#mod" aria-controls="mod" role="tab" data-toggle="tab"><?php echo grw_i('Review Moderation'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane<?php if (!$grw_setting_page) { ?> active<?php } ?>" id="about">
            <div class="row">
                <div class="col-sm-6">
                    <h4><?php echo grw_i('Google Reviews Widget for WordPress'); ?></h4>
                    <p><?php echo grw_i('Google Reviews plugin is an easy and fast way to integrate Google business reviews right into your WordPress website. This plugin works instantly and keep all Google places and reviews in WordPress database thus it has no depend on external services.'); ?></p>
                    <p><?php echo grw_i('To use a widget, please do follow:'); ?></p>
                    <ol>
                        <li>Go to menu <b>"Appearance"</b> -> <b>"Widgets"</b></li>
                        <li>Move "Google Reviews Widget" widget to sidebar</li>
                        <li>Enter search query of your business place in "Location of place" field and click "Search Place"</li>
                        <li>Select your found place in the panel below and click "Save Place and Reviews"</li>
                        <li>"Google Place Name" and "Google Place ID" must be filled, if so click "Save" button to save the widget</li>
                    </ol>
                    <p><?php echo grw_i('Feel free to contact us by email <a href="mailto:support@richplugins.com">support@richplugins.com</a>.'); ?></p>
                    <p><?php echo grw_i('<b>Like this plugin? Give it a like on social:</b>'); ?></p>
                    <div class="row">
                        <div class="col-sm-4">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.6&appId=1501100486852897";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                            <div class="fb-like" data-href="https://richplugins.com/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                        </div>
                        <div class="col-sm-4 twitter">
                            <a href="https://twitter.com/richplugins" class="twitter-follow-button" data-show-count="false">Follow @RichPlugins</a>
                            <script>!function (d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                    if (!d.getElementById(id)) {
                                        js = d.createElement(s);
                                        js.id = id;
                                        js.src = p + '://platform.twitter.com/widgets.js';
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }
                                }(document, 'script', 'twitter-wjs');</script>
                        </div>
                        <div class="col-sm-4 googleplus">
                            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://plus.google.com/101080686931597182099"></div>
                            <script type="text/javascript">
                                window.___gcfg = { lang: 'en-US' };
                                (function () {
                                    var po = document.createElement('script');
                                    po.type = 'text/javascript';
                                    po.async = true;
                                    po.src = 'https://apis.google.com/js/plusone.js';
                                    var s = document.getElementsByTagName('script')[0];
                                    s.parentNode.insertBefore(po, s);
                                })();
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <br>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/lmaTBmvDPKk?rel=0" allowfullscreen=""></iframe>
                    </div>
                </div>
            </div>
            <hr>
            <h4>Get More Features with Google Reviews Business!</h4>
            <p><a href="https://richplugins.com/google-reviews-pro-wordpress-plugin" target="_blank" style="color:#00bf54;font-size:16px;text-decoration:underline;">Upgrade to Google Reviews Business</a></p>
            <p>* Trying to get more than 5 Google reviews</p>
            <p>* Google Rich Snippets (schema.org)</p>
            <p>* Support shortcode</p>
            <p>* Powerful <b>Shortcode Builder</b></p>
            <p>* Slider/Grid theme to show G+ reviews in testimonials section</p>
            <p>* Google Trust Badge (right/left fixed or inner)</p>
            <p>* 'Write a review' button to available leave Google review directly on your website</p>
            <p>* Trim long reviews with "read more" link</p>
            <p>* Show/hide business photo and avatars</p>
            <p>* Custom business place photo</p>
            <p>* Minimum rating filter</p>
            <p>* Pagination, Sorting</p>
            <p>* Moderation G+ reviews</p>
            <p>* Priority support</p>
        </div>
        <div role="tabpanel" class="tab-pane<?php if ($grw_setting_page) { ?> active<?php } ?>" id="setting">
            <h4><?php echo grw_i('Google Reviews Widget Setting'); ?></h4>
            <!-- Configuration form -->
            <form method="POST" enctype="multipart/form-data">
                <?php wp_nonce_field('grw-wpnonce_grw_settings', 'grw-form_nonce_grw_settings'); ?>
                <div class="form-group">
                    <label class="control-label" for="grw_google_api_key"><?php echo grw_i('Google Places API Key'); ?></label>
                    <input class="form-control" type="text" id="grw_google_api_key" name="grw_google_api_key" value="<?php echo esc_attr($grw_google_api_key); ?>">
                    <?php if (isset($grw_google_api_key_error)) {?>
                    <div class="alert alert-dismissible alert-danger">
                        The Google API Key is wrong, error message: <?php echo $grw_google_api_key_error; ?><br>
                        Please get the correct key by instruction below ↓
                    </div>
                    <?php } ?>
                    <small>
                        <b>How to get Google Places API key</b>:<br>
                        1. Go to <a href="https://developers.google.com/places/web-service/get-api-key#get_an_api_key" target="_blank">Google Places API Key</a><br>
                        2. Click by '<b>GET A KEY</b>' button<br>
                        3. Fill the name, agree term and click by '<b>NEXT</b>' button<br>
                        4. Copy key to plugin field<br>
                        <iframe src="//www.youtube.com/embed/uW-PTKeZAXs?rel=0" allowfullscreen=""></iframe>
                    </small>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo grw_i('Google Places API language'); ?></label>
                    <select class="form-control" id="grw_language" name="grw_language">
                        <option value="" <?php selected('', $grw_language); ?>><?php echo grw_i('Disable'); ?></option>
                        <option value="ar" <?php selected('ar', $grw_language); ?>><?php echo grw_i('Arabic'); ?></option>
                        <option value="bg" <?php selected('bg', $grw_language); ?>><?php echo grw_i('Bulgarian'); ?></option>
                        <option value="bn" <?php selected('bn', $grw_language); ?>><?php echo grw_i('Bengali'); ?></option>
                        <option value="ca" <?php selected('ca', $grw_language); ?>><?php echo grw_i('Catalan'); ?></option>
                        <option value="cs" <?php selected('cs', $grw_language); ?>><?php echo grw_i('Czech'); ?></option>
                        <option value="da" <?php selected('da', $grw_language); ?>><?php echo grw_i('Danish'); ?></option>
                        <option value="de" <?php selected('de', $grw_language); ?>><?php echo grw_i('German'); ?></option>
                        <option value="el" <?php selected('el', $grw_language); ?>><?php echo grw_i('Greek'); ?></option>
                        <option value="en" <?php selected('en', $grw_language); ?>><?php echo grw_i('English'); ?></option>
                        <option value="en-AU" <?php selected('en-AU', $grw_language); ?>><?php echo grw_i('English (Australian)'); ?></option>
                        <option value="en-GB" <?php selected('en-GB', $grw_language); ?>><?php echo grw_i('English (Great Britain)'); ?></option>
                        <option value="es" <?php selected('es', $grw_language); ?>><?php echo grw_i('Spanish'); ?></option>
                        <option value="eu" <?php selected('eu', $grw_language); ?>><?php echo grw_i('Basque'); ?></option>
                        <option value="eu" <?php selected('eu', $grw_language); ?>><?php echo grw_i('Basque'); ?></option>
                        <option value="fa" <?php selected('fa', $grw_language); ?>><?php echo grw_i('Farsi'); ?></option>
                        <option value="fi" <?php selected('fi', $grw_language); ?>><?php echo grw_i('Finnish'); ?></option>
                        <option value="fil" <?php selected('fil', $grw_language); ?>><?php echo grw_i('Filipino'); ?></option>
                        <option value="fr" <?php selected('fr', $grw_language); ?>><?php echo grw_i('French'); ?></option>
                        <option value="gl" <?php selected('gl', $grw_language); ?>><?php echo grw_i('Galician'); ?></option>
                        <option value="gu" <?php selected('gu', $grw_language); ?>><?php echo grw_i('Gujarati'); ?></option>
                        <option value="hi" <?php selected('hi', $grw_language); ?>><?php echo grw_i('Hindi'); ?></option>
                        <option value="hr" <?php selected('hr', $grw_language); ?>><?php echo grw_i('Croatian'); ?></option>
                        <option value="hu" <?php selected('hu', $grw_language); ?>><?php echo grw_i('Hungarian'); ?></option>
                        <option value="id" <?php selected('id', $grw_language); ?>><?php echo grw_i('Indonesian'); ?></option>
                        <option value="it" <?php selected('it', $grw_language); ?>><?php echo grw_i('Italian'); ?></option>
                        <option value="iw" <?php selected('iw', $grw_language); ?>><?php echo grw_i('Hebrew'); ?></option>
                        <option value="ja" <?php selected('ja', $grw_language); ?>><?php echo grw_i('Japanese'); ?></option>
                        <option value="kn" <?php selected('kn', $grw_language); ?>><?php echo grw_i('Kannada'); ?></option>
                        <option value="ko" <?php selected('ko', $grw_language); ?>><?php echo grw_i('Korean'); ?></option>
                        <option value="lt" <?php selected('lt', $grw_language); ?>><?php echo grw_i('Lithuanian'); ?></option>
                        <option value="lv" <?php selected('lv', $grw_language); ?>><?php echo grw_i('Latvian'); ?></option>
                        <option value="ml" <?php selected('ml', $grw_language); ?>><?php echo grw_i('Malayalam'); ?></option>
                        <option value="mr" <?php selected('mr', $grw_language); ?>><?php echo grw_i('Marathi'); ?></option>
                        <option value="nl" <?php selected('nl', $grw_language); ?>><?php echo grw_i('Dutch'); ?></option>
                        <option value="no" <?php selected('no', $grw_language); ?>><?php echo grw_i('Norwegian'); ?></option>
                        <option value="pl" <?php selected('pl', $grw_language); ?>><?php echo grw_i('Polish'); ?></option>
                        <option value="pt" <?php selected('pt', $grw_language); ?>><?php echo grw_i('Portuguese'); ?></option>
                        <option value="pt-BR" <?php selected('pt-BR', $grw_language); ?>><?php echo grw_i('Portuguese (Brazil)'); ?></option>
                        <option value="pt-PT" <?php selected('pt-PT', $grw_language); ?>><?php echo grw_i('Portuguese (Portugal)'); ?></option>
                        <option value="ro" <?php selected('ro', $grw_language); ?>><?php echo grw_i('Romanian'); ?></option>
                        <option value="ru" <?php selected('ru', $grw_language); ?>><?php echo grw_i('Russian'); ?></option>
                        <option value="sk" <?php selected('sk', $grw_language); ?>><?php echo grw_i('Slovak'); ?></option>
                        <option value="sl" <?php selected('sl', $grw_language); ?>><?php echo grw_i('Slovenian'); ?></option>
                        <option value="sr" <?php selected('sr', $grw_language); ?>><?php echo grw_i('Serbian'); ?></option>
                        <option value="sv" <?php selected('sv', $grw_language); ?>><?php echo grw_i('Swedish'); ?></option>
                        <option value="ta" <?php selected('ta', $grw_language); ?>><?php echo grw_i('Tamil'); ?></option>
                        <option value="te" <?php selected('te', $grw_language); ?>><?php echo grw_i('Telugu'); ?></option>
                        <option value="th" <?php selected('th', $grw_language); ?>><?php echo grw_i('Thai'); ?></option>
                        <option value="tl" <?php selected('tl', $grw_language); ?>><?php echo grw_i('Tagalog'); ?></option>
                        <option value="tr" <?php selected('tr', $grw_language); ?>><?php echo grw_i('Turkish'); ?></option>
                        <option value="uk" <?php selected('uk', $grw_language); ?>><?php echo grw_i('Ukrainian'); ?></option>
                        <option value="vi" <?php selected('vi', $grw_language); ?>><?php echo grw_i('Vietnamese'); ?></option>
                        <option value="zh-CN" <?php selected('zh-CN', $grw_language); ?>><?php echo grw_i('Chinese (Simplified)'); ?></option>
                        <option value="zh-TW" <?php selected('zh-TW', $grw_language); ?>><?php echo grw_i('Chinese (Traditional)'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control" type="checkbox" id="grw_install_db" name="grw_install_db" >
                    <label class="control-label" for="grw_install_db"><?php echo grw_i('Re-create the DB tables for the plugin (service option)'); ?></label>
                </div>
                <p class="submit" style="text-align: left">
                    <input name="grw_setting" type="submit" value="Save" class="button-primary button" tabindex="4">
                </p>
            </form>
            <hr>
            <!-- Enable/disable Google Reviews Widget toggle -->
            <form method="POST" action="?page=grw&amp;grw_active=<?php echo (string)((int)($grw_enabled != true)); ?>">
                <?php wp_nonce_field('grw-wpnonce_grw_active', 'grw-form_nonce_grw_active'); ?>
                <span class="status">
                    <?php echo grw_i('Google Reviews Widget is currently '). '<b>' .($grw_enabled ? grw_i('enabled') : grw_i('disabled')). '</b>'; ?>
                </span>
                <input type="submit" name="grw_active" class="button" value="<?php echo $grw_enabled ? grw_i('Disable') : grw_i('Enable'); ?>" />
            </form>
            <hr>
            <!-- Debug information -->
            <button class="btn btn-primary btn-small" type="button" data-toggle="collapse" data-target="#debug" aria-expanded="false" aria-controls="debug">
                <?php echo grw_i('Debug Information'); ?>
            </button>
            <div id="debug" class="collapse">
                <textarea style="width:90%; height:200px;" onclick="this.select();return false;" readonly><?php
                    rplg_debug(GRW_VERSION, grw_options(), 'widget_grw_widget');
                    grw_debug();
                ?></textarea>
            </div>
            <div style="max-width:700px"><?php echo grw_i('Feel free to contact support team by support@richplugins.com for any issues but please don\'t forget to provide debug information that you can get by click on \'Debug Information\' button.'); ?></div>
            <hr>
            <!-- Reset form -->
            <form action="?page=grw" method="POST">
                <?php wp_nonce_field('grw-wpnonce_grw_reset', 'grw-form_nonce_grw_reset'); ?>
                <p>
                    <input type="submit" value="Reset" name="reset" onclick="return confirm('<?php echo grw_i('Are you sure you want to reset the Google Reviews Widget plugin?'); ?>')" class="button" />
                    <?php echo grw_i('This removes all plugin-specific settings.') ?>
                </p>
                <p>
                    <input type="checkbox" id="reset_db" name="reset_db">
                    <label for="reset_db"><?php echo grw_i('Remove all data including Google Reviews'); ?></label>
                </p>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="mod">
            <h4><?php echo grw_i('Moderation available in Google Reviews Business plugin:'); ?></h4>
            <a href="https://richplugins.com/google-reviews-pro-wordpress-plugin" target="_blank" style="color:#00bf54;font-size:16px;text-decoration:underline;"><?php echo grw_i('Upgrade to Business'); ?></a>
        </div>
        <div role="tabpanel" class="tab-pane" id="shortcode">
            <h4><?php echo grw_i('Shortcode Builder available in Google Reviews Business plugin:'); ?></h4>
            <a href="https://richplugins.com/google-reviews-pro-wordpress-plugin" target="_blank" style="color:#00bf54;font-size:16px;text-decoration:underline;"><?php echo grw_i('Upgrade to Business'); ?></a>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('a[data-toggle="tab"]').on('click', function(e)  {
        var active = $(this).attr('href');
        $('.tab-content ' + active).addClass('active').show().siblings().hide();
        $(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();
    });
    $('button[data-toggle="collapse"]').click(function () {
        $target = $(this);
        $collapse = $target.next();
        $collapse.slideToggle(500);
    });
});
</script>