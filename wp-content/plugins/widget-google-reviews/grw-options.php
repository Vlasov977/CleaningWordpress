<?php if (isset($title)) { ?>
<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo grw_i('Widget title'); ?>" />
    </div>
</div>
<?php } ?>

<?php global $wp_version; if (version_compare($wp_version, '3.5', '>=')) { wp_enqueue_media(); ?>
<div class="form-group">
    <div class="col-sm-12">
        <img id="<?php echo $this->get_field_id('place_photo_img'); ?>" src="<?php echo $place_photo; ?>" alt="<?php echo $place_name; ?>" class="grw-place-photo-img" style="display:<?php if ($place_photo) { ?>inline-block<?php } else { ?>none<?php } ?>;width:32px;height:32px;border-radius:50%;">
        <a id="<?php echo $this->get_field_id('place_photo_btn'); ?>" href="#" class="grw-place-photo-btn"><?php echo grw_i('Change Place photo'); ?></a>
        <input type="hidden" id="<?php echo $this->get_field_id('place_photo'); ?>" name="<?php echo $this->get_field_name('place_photo'); ?>" value="<?php echo $place_photo; ?>" class="form-control grw-place-photo" tabindex="2"/>
    </div>
</div>
<?php } ?>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('place_name'); ?>" name="<?php echo $this->get_field_name('place_name'); ?>" value="<?php echo $place_name; ?>" class="form-control grw-google-place-name" placeholder="<?php echo grw_i('Google Place Name'); ?>" readonly />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('place_id'); ?>" name="<?php echo $this->get_field_name('place_id'); ?>" value="<?php echo $place_id; ?>" class="form-control grw-google-place-id" placeholder="<?php echo grw_i('Google Place ID'); ?>" readonly />
    </div>
</div>

<!-- Review Options -->
<h4 class="rplg-options-toggle"><?php echo grw_i('Review Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo grw_i('Try to get more than 5 Google reviews'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo grw_i('Enable Google Rich Snippet (schema.org)'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label><?php echo grw_i('Pagination'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('pagination'); ?>" name="<?php echo $this->get_field_name('pagination'); ?>" value="<?php echo $pagination; ?>" placeholder="5" class="form-control"/>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <?php echo grw_i('Sorting'); ?>
            <select class="form-control" disabled >
                <option value=""><?php echo grw_i('Default'); ?></option>
                <option value="1"><?php echo grw_i('Most recent'); ?></option>
                <option value="2"><?php echo grw_i('Most oldest'); ?></option>
                <option value="3"><?php echo grw_i('Highest score'); ?></option>
                <option value="4"><?php echo grw_i('Lowest score'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <?php echo grw_i('Minimum Review Rating'); ?>
            <select class="form-control" disabled >
                <option value=""><?php echo grw_i('No filter'); ?></option>
                <option value="5"><?php echo grw_i('5 Stars'); ?></option>
                <option value="4"><?php echo grw_i('4 Stars'); ?></option>
                <option value="3"><?php echo grw_i('3 Stars'); ?></option>
                <option value="2"><?php echo grw_i('2 Stars'); ?></option>
                <option value="1"><?php echo grw_i('1 Star'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="rplg-pro">
            <?php echo grw_i('These features are available in Google Reviews Business plugin: '); ?>
            <a href="https://richplugins.com/google-reviews-pro-wordpress-plugin" target="_blank">
                <?php echo grw_i('Upgrade to Business'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Display Options -->
<h4 class="rplg-options-toggle"><?php echo grw_i('Display Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo grw_i('Hide business photo'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo grw_i('Hide user avatars'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo grw_i('Disable links to G+ user profile'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label>
                <input class="form-control" type="checkbox" disabled />
                <?php echo grw_i('Enable \'Write a review\' button'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('dark_theme'); ?>" name="<?php echo $this->get_field_name('dark_theme'); ?>" type="checkbox" value="1" <?php checked('1', $dark_theme); ?> class="form-control" />
                <?php echo grw_i('Dark background'); ?>
            </label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label><?php echo grw_i('Review limit before \'read more\' link'); ?></label>
            <input class="form-control" type="text" placeholder="for instance: 120" disabled />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo grw_i('Widget theme'); ?>
            <select id="<?php echo $this->get_field_id('view_mode'); ?>" name="<?php echo $this->get_field_name('view_mode'); ?>" class="form-control">
                <option value="list" <?php selected('list', $view_mode); ?>><?php echo grw_i('Review list'); ?></option>
                <option value="slider" <?php selected('slider', $view_mode); ?> disabled><?php echo grw_i('Reviews Slider'); ?></option>
                <option value="grid" <?php selected('grid', $view_mode); ?> disabled><?php echo grw_i('Reviews Grid'); ?></option>
                <option value="badge" <?php selected('badge', $view_mode); ?> disabled><?php echo grw_i('Google Badge: right'); ?></option>
                <option value="badge_left" <?php selected('badge_left', $view_mode); ?> disabled><?php echo grw_i('Google Badge: left'); ?></option>
                <option value="badge_inner" <?php selected('badge_inner', $view_mode); ?> disabled><?php echo grw_i('Google Badge: embed'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label for="<?php echo $this->get_field_id('max_width'); ?>"><?php echo grw_i('Maximum width'); ?></label>
            <input id="<?php echo $this->get_field_id('max_width'); ?>" name="<?php echo $this->get_field_name('max_width'); ?>" value="<?php echo $max_width; ?>" class="form-control" type="text" placeholder="for instance: 300px" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label for="<?php echo $this->get_field_id('max_height'); ?>"><?php echo grw_i('Maximum height'); ?></label>
            <input id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" value="<?php echo $max_height; ?>" class="form-control" type="text" placeholder="for instance: 500px" />
        </div>
    </div>
    <div class="form-group">
        <div class="rplg-pro">
            <?php echo grw_i('<b>Slider</b>, <b>Grid</b>, <b>Badge</b> themes and other features are available in Google Reviews Business plugin: '); ?>
            <a href="https://richplugins.com/google-reviews-pro-wordpress-plugin" target="_blank">
                <?php echo grw_i('Upgrade to Business'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Slider Options -->
<h4 class="rplg-options-toggle"><?php echo grw_i('Slider Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group">
        <div class="rplg-pro">
            <?php echo grw_i('<b>Slider</b> is available in Google Reviews Business plugin: '); ?>
            <a href="https://richplugins.com/google-reviews-pro-wordpress-plugin" target="_blank">
                <?php echo grw_i('Upgrade to Business'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Advance Options -->
<h4 class="rplg-options-toggle"><?php echo grw_i('Advance Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('open_link'); ?>" name="<?php echo $this->get_field_name('open_link'); ?>" type="checkbox" value="1" <?php checked('1', $open_link); ?> class="form-control" />
                <?php echo grw_i('Open links in new Window'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('nofollow_link'); ?>" name="<?php echo $this->get_field_name('nofollow_link'); ?>" type="checkbox" value="1" <?php checked('1', $nofollow_link); ?> class="form-control" />
                <?php echo grw_i('Use no follow links'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo grw_i('Language of reviews'); ?>
            <select id="<?php echo $this->get_field_id('reviews_lang'); ?>" name="<?php echo $this->get_field_name('reviews_lang'); ?>" class="form-control">
                <option value="" <?php selected('', $reviews_lang); ?>><?php echo grw_i('Disable'); ?></option>
                <option value="ar" <?php selected('ar', $reviews_lang); ?>><?php echo grw_i('Arabic'); ?></option>
                <option value="bg" <?php selected('bg', $reviews_lang); ?>><?php echo grw_i('Bulgarian'); ?></option>
                <option value="bn" <?php selected('bn', $reviews_lang); ?>><?php echo grw_i('Bengali'); ?></option>
                <option value="ca" <?php selected('ca', $reviews_lang); ?>><?php echo grw_i('Catalan'); ?></option>
                <option value="cs" <?php selected('cs', $reviews_lang); ?>><?php echo grw_i('Czech'); ?></option>
                <option value="da" <?php selected('da', $reviews_lang); ?>><?php echo grw_i('Danish'); ?></option>
                <option value="de" <?php selected('de', $reviews_lang); ?>><?php echo grw_i('German'); ?></option>
                <option value="el" <?php selected('el', $reviews_lang); ?>><?php echo grw_i('Greek'); ?></option>
                <option value="en" <?php selected('en', $reviews_lang); ?>><?php echo grw_i('English'); ?></option>
                <option value="en-AU" <?php selected('en-AU', $reviews_lang); ?>><?php echo grw_i('English (Australian)'); ?></option>
                <option value="en-GB" <?php selected('en-GB', $reviews_lang); ?>><?php echo grw_i('English (Great Britain)'); ?></option>
                <option value="es" <?php selected('es', $reviews_lang); ?>><?php echo grw_i('Spanish'); ?></option>
                <option value="eu" <?php selected('eu', $reviews_lang); ?>><?php echo grw_i('Basque'); ?></option>
                <option value="eu" <?php selected('eu', $reviews_lang); ?>><?php echo grw_i('Basque'); ?></option>
                <option value="fa" <?php selected('fa', $reviews_lang); ?>><?php echo grw_i('Farsi'); ?></option>
                <option value="fi" <?php selected('fi', $reviews_lang); ?>><?php echo grw_i('Finnish'); ?></option>
                <option value="fil" <?php selected('fil', $reviews_lang); ?>><?php echo grw_i('Filipino'); ?></option>
                <option value="fr" <?php selected('fr', $reviews_lang); ?>><?php echo grw_i('French'); ?></option>
                <option value="gl" <?php selected('gl', $reviews_lang); ?>><?php echo grw_i('Galician'); ?></option>
                <option value="gu" <?php selected('gu', $reviews_lang); ?>><?php echo grw_i('Gujarati'); ?></option>
                <option value="hi" <?php selected('hi', $reviews_lang); ?>><?php echo grw_i('Hindi'); ?></option>
                <option value="hr" <?php selected('hr', $reviews_lang); ?>><?php echo grw_i('Croatian'); ?></option>
                <option value="hu" <?php selected('hu', $reviews_lang); ?>><?php echo grw_i('Hungarian'); ?></option>
                <option value="id" <?php selected('id', $reviews_lang); ?>><?php echo grw_i('Indonesian'); ?></option>
                <option value="it" <?php selected('it', $reviews_lang); ?>><?php echo grw_i('Italian'); ?></option>
                <option value="iw" <?php selected('iw', $reviews_lang); ?>><?php echo grw_i('Hebrew'); ?></option>
                <option value="ja" <?php selected('ja', $reviews_lang); ?>><?php echo grw_i('Japanese'); ?></option>
                <option value="kn" <?php selected('kn', $reviews_lang); ?>><?php echo grw_i('Kannada'); ?></option>
                <option value="ko" <?php selected('ko', $reviews_lang); ?>><?php echo grw_i('Korean'); ?></option>
                <option value="lt" <?php selected('lt', $reviews_lang); ?>><?php echo grw_i('Lithuanian'); ?></option>
                <option value="lv" <?php selected('lv', $reviews_lang); ?>><?php echo grw_i('Latvian'); ?></option>
                <option value="ml" <?php selected('ml', $reviews_lang); ?>><?php echo grw_i('Malayalam'); ?></option>
                <option value="mr" <?php selected('mr', $reviews_lang); ?>><?php echo grw_i('Marathi'); ?></option>
                <option value="nl" <?php selected('nl', $reviews_lang); ?>><?php echo grw_i('Dutch'); ?></option>
                <option value="no" <?php selected('no', $reviews_lang); ?>><?php echo grw_i('Norwegian'); ?></option>
                <option value="pl" <?php selected('pl', $reviews_lang); ?>><?php echo grw_i('Polish'); ?></option>
                <option value="pt" <?php selected('pt', $reviews_lang); ?>><?php echo grw_i('Portuguese'); ?></option>
                <option value="pt-BR" <?php selected('pt-BR', $reviews_lang); ?>><?php echo grw_i('Portuguese (Brazil)'); ?></option>
                <option value="pt-PT" <?php selected('pt-PT', $reviews_lang); ?>><?php echo grw_i('Portuguese (Portugal)'); ?></option>
                <option value="ro" <?php selected('ro', $reviews_lang); ?>><?php echo grw_i('Romanian'); ?></option>
                <option value="ru" <?php selected('ru', $reviews_lang); ?>><?php echo grw_i('Russian'); ?></option>
                <option value="sk" <?php selected('sk', $reviews_lang); ?>><?php echo grw_i('Slovak'); ?></option>
                <option value="sl" <?php selected('sl', $reviews_lang); ?>><?php echo grw_i('Slovenian'); ?></option>
                <option value="sr" <?php selected('sr', $reviews_lang); ?>><?php echo grw_i('Serbian'); ?></option>
                <option value="sv" <?php selected('sv', $reviews_lang); ?>><?php echo grw_i('Swedish'); ?></option>
                <option value="ta" <?php selected('ta', $reviews_lang); ?>><?php echo grw_i('Tamil'); ?></option>
                <option value="te" <?php selected('te', $reviews_lang); ?>><?php echo grw_i('Telugu'); ?></option>
                <option value="th" <?php selected('th', $reviews_lang); ?>><?php echo grw_i('Thai'); ?></option>
                <option value="tl" <?php selected('tl', $reviews_lang); ?>><?php echo grw_i('Tagalog'); ?></option>
                <option value="tr" <?php selected('tr', $reviews_lang); ?>><?php echo grw_i('Turkish'); ?></option>
                <option value="uk" <?php selected('uk', $reviews_lang); ?>><?php echo grw_i('Ukrainian'); ?></option>
                <option value="vi" <?php selected('vi', $reviews_lang); ?>><?php echo grw_i('Vietnamese'); ?></option>
                <option value="zh-CN" <?php selected('zh-CN', $reviews_lang); ?>><?php echo grw_i('Chinese (Simplified)'); ?></option>
                <option value="zh-TW" <?php selected('zh-TW', $reviews_lang); ?>><?php echo grw_i('Chinese (Traditional)'); ?></option>
            </select>
        </div>
    </div>
</div>