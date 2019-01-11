<?php

require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'foundation_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 */
function foundation_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */


	$plugins = array(
		/** This is an example of how to include a plugin pre-packaged with a theme */
		array(
			'name'     => 'Advanced Custom Fields Pro (b3JkZXJfaWQ9NTU1MDd8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE1LTA1LTA2IDExOjUzOjM1)', // The plugin name
			'slug'     => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name)
			'source'   => 'http://do.ready-for-feedback.com/plugins/advanced-custom-fields-pro.zip', // The plugin source
			'required' => false,
		),
		array(
			'name'     => 'ACF Gravity Forms field', // The plugin name
			'slug'     => 'Gravity-Forms-ACF-Field-master', // The plugin slug (typically the folder name)
			'source'   => 'http://do.ready-for-feedback.com/plugins/acf-gravity-forms-field.zip', // The plugin source
			'required' => false,
		),
		array(
			'name'     => 'Black Studio TinyMCE Widget', // The plugin name
			'slug'     => 'black-studio-tinymce-widget', // The plugin slug (typically the folder name)
			'required' => false,
		),
		array(
			'name'     => 'Custom Post Type UI', // The plugin name
			'slug'     => 'custom-post-type-ui', // The plugin slug (typically the folder name)
			'required' => false,
		),
		array(
			'name'     => 'Duplicator', // The plugin name
			'slug'     => 'duplicator', // The plugin slug (typically the folder name)
			'required' => false,
		),
		array(
			'name'     => 'Google Analytics by MonsterInsights', // The plugin name
			'slug'     => 'google-analytics-for-wordpress', // The plugin slug (typically the folder name)
			'required' => false,
		),
		array(
			'name'     => 'Gravity Forms (86a265e9644d0b79e4ccce71a582fc7e)', // The plugin name
			'slug'     => 'gravityforms', // The plugin slug (typically the folder name)
			'source'   => 'http://do.ready-for-feedback.com/plugins/gravityforms.zip', // The plugin source
			'required' => false,
		),
		array(
			'name'     => 'Login LockDown', // The plugin name
			'slug'     => 'login-lockdown', // The plugin slug (typically the folder name)
			'required' => false,
		),
		array(
			'name'     => 'Compress JPEG & PNG images - TinyPNG (COttVeGWk1kDm3HjgCk5rEMZlf3JAah4) ', // The plugin name
			'slug'     => 'tiny-compress-images', // The plugin slug (typically the folder name)
			'required' => false,
		),
		/*array(
			'name'     => 'UserBack', // The plugin name
			'slug'     => 'userback', // The plugin slug (typically the folder name)
			'source'   => 'http://do.ready-for-feedback.com/plugins/userback.zip', // The plugin source
			'required' => false,
		),*/
		array(
			'name'     => 'Usersnap', // The plugin name
			'slug'     => 'usersnap', // The plugin slug (typically the folder name)
			'required' => false,
		),
		array(
			'name'     => 'File Checker', // The plugin name
			'slug'     => 'filter-files', // The plugin slug (typically the folder name)
			'source'   => 'http://do.ready-for-feedback.com/plugins/filter-files.zip', // The plugin source
			'required' => false,
		),
		array(
			'name'     => 'Yoast SEO', // The plugin name
			'slug'     => 'wordpress-seo', // The plugin slug (typically the folder name)
			'required' => false,
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 */
	$config = array(
		'id'           => 'foundation',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => false,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}