<?php

/**
 * Class Strong_Testimonials_Settings_Compat
 *
 * @since 2.28.0
 */
class Strong_Testimonials_Settings_Compat {

	const TAB_NAME = 'compat';

	const OPTION_NAME = 'wpmtst_compat_options';

	const GROUP_NAME = 'wpmtst-compat-group';

	var $options;

	/**
	 * Strong_Testimonials_Settings_Compat constructor.
	 */
	public function __construct() {
		$this->options = get_option( self::OPTION_NAME );
		$this->add_actions();
	}

	/**
	 * Add actions and filters.
	 */
	public function add_actions() {
		add_action( 'wpmtst_register_settings', array( $this, 'register_settings' ) );
		add_action( 'wpmtst_settings_tabs', array( $this, 'register_tab' ), 3, 2 );
		add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_settings_page' ) );
		add_action( 'wp_ajax_wpmtst_add_lazyload_pair', array( $this, 'add_lazyload_pair' ) );
	}

	/**
	 * Register settings tab.
	 *
	 * @param $active_tab
	 * @param $url
	 */
	public function register_tab( $active_tab, $url ) {
		printf( '<a href="%s" class="nav-tab %s">%s</a>',
		        esc_url( add_query_arg( 'tab', self::TAB_NAME, $url ) ),
		        esc_attr( $active_tab == self::TAB_NAME ? 'nav-tab-active' : '' ),
		        __( 'Compatibility', 'strong-testimonials' )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting( self::GROUP_NAME, self::OPTION_NAME, array( $this, 'sanitize_options' ) );
	}

	/**
	 * Register settings page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function register_settings_page( $pages ) {
		$pages[ self::TAB_NAME ] = array( $this, 'settings_page' );

		return $pages;
	}

	/**
	 * Sanitize settings.
	 *
	 * @param $input
	 * @since 2.28.0
	 * @since 2.31.0 controller
	 * @since 2.31.0 lazyload
	 * @return array
	 */
	public function sanitize_options( $input ) {
		$input['page_loading'] = sanitize_text_field( $input['page_loading'] );

		if ( 'general' == $input['page_loading'] ) {
			$input['prerender']      = 'all';
			$input['ajax']['method'] = 'universal';
		} else {
			$input['prerender']      = sanitize_text_field( $input['prerender'] );
			$input['ajax']['method'] = sanitize_text_field( $input['ajax']['method'] );
		}

		$input['ajax']['universal_timer'] = floatval( sanitize_text_field( $input['ajax']['universal_timer'] ) );
		$input['ajax']['observer_timer']  = floatval( sanitize_text_field( $input['ajax']['observer_timer'] ) );
		$input['ajax']['container_id']    = sanitize_text_field( $input['ajax']['container_id'] );
		$input['ajax']['addednode_id']    = sanitize_text_field( $input['ajax']['addednode_id'] );
		$input['ajax']['event']           = sanitize_text_field( $input['ajax']['event'] );
		$input['ajax']['script']          = sanitize_text_field( $input['ajax']['script'] );

		$input['controller']['initialize_on'] = sanitize_text_field( $input['controller']['initialize_on'] );

		// FIXME: Special handling until proper use of default values in v3.0
        $default = array(
	        'enabled' => false,
	        'classes' => array(
		        array(
			        'start'  => '',
			        'finish' => '',
		        ),
	        ),
        );

		if ( ! isset( $input['lazyload'] ) ) {

			$input['lazyload'] = $default;

		} else {

			$input['lazyload']['enabled'] = wpmtst_sanitize_checkbox( $input['lazyload'], 'enabled' );

			if ( isset( $input['lazyload']['classes'] ) && $input['lazyload']['classes'] ) {

				// May be multiple pairs.
				foreach ( $input['lazyload']['classes'] as $key => $classes ) {

				    // Sanitize classes or remove empty pairs.
					// Reduce multiple empty pairs down to default value of single empty pair.
					if ( $classes['start'] || $classes['finish'] ) {
						$input['lazyload']['classes'][ $key ]['start']  = str_replace( '.', '', sanitize_text_field( $classes['start'] ) );
						$input['lazyload']['classes'][ $key ]['finish'] = str_replace( '.', '', sanitize_text_field( $classes['finish'] ) );
					} else {
						unset( $input['lazyload']['classes'][$key] );
					}

					if ( ! count( $input['lazyload']['classes'] ) ) {
						$input['lazyload'] = $default;
					}

				}

			} else {

				$input['lazyload'] = $default['classes'];

			}

		}

		return $input;
	}

	/**
	 * Print settings page.
	 */
	public function settings_page() {
		settings_fields( self::GROUP_NAME );
		$this->settings_top();
	}

	/**
	 * Compatibility settings
	 *
	 * @since 2.31.0 controller
	 * @since 2.31.0 lazyload
	 */
	public function settings_top() {
		$this->settings_intro();
		$this->settings_page_loading();
		$this->settings_prerender();
		$this->settings_monitor();
		$this->settings_controller();
		$this->settings_lazyload();
	}

	/**
	 * Settings intro
	 */
	public function settings_intro() {
		?>
		<h2><?php _e( 'Common Scenarios', 'strong-testimonials' ); ?></h2>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td>
					<div class="scenarios">

						<div class="row header">
							<div>
								<?php _e( 'Symptom', 'strong-testimonials' ); ?>
							</div>
							<div>
								<?php _e( 'Possible Cause', 'strong-testimonials' ); ?>
							</div>
							<div>
								<?php _e( 'Try', 'strong-testimonials' ); ?>
							</div>
						</div>

						<div class="row">
							<div>
								<p><strong><?php _e( 'Views not working', 'strong-testimonials' ); ?></strong></p>
								<p><?php _e( 'A testimonial view does not appear correctly the <strong>first time</strong> you view the page but it does when you <strong>refresh</strong> the page.', 'strong-testimonials' ); ?></p>
								<p><?php _e( 'For example, it has no style, no pagination, or the slider has not started.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><?php _e( 'Your site uses <strong>Ajax page loading</strong> &ndash; also known as page animations, transition effects or Pjax (pushState Ajax) &ndash; provided by your theme or another plugin.', 'strong-testimonials' ); ?></p>
								<p><?php _e( 'Instead of loading the entire page, this technique fetches only the new content.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><strong><?php _e( 'Ajax Page Loading', 'strong-testimonials' ); ?>:</strong> <?php _e( 'General', 'strong-testimonials' ); ?></p>
								<p>
									<a href="#" id="set-scenario-1">
										<?php /* translators: link text on Settings > Compatibility tab */ _e( 'Set this now', 'strong-testimonials' ); ?>
									</a>
								</p>
							</div>
						</div>

						<div class="row">
							<div>
								<p><strong><?php _e( 'Slider never starts', 'strong-testimonials' ); ?></strong></p>
								<p><?php _e( 'A testimonial slider does not start or is missing navigation controls.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><?php _e( 'The page is very busy loading image galleries, other sliders or third-party resources like social media posts.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><strong><?php _e( 'Load Event', 'strong-testimonials' ); ?>:</strong> <?php _e( 'window load', 'strong-testimonials' ); ?></p>
							</div>
						</div>

						<div class="row">
							<div>
								<p><strong><?php _e( 'Masonry layout not working', 'strong-testimonials' ); ?></strong></p>
								<p><?php _e( 'A testimonial view with the Masonry layout has only one column or works inconsistently in different browsers or devices.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><?php _e( 'The page is very busy loading image galleries, other sliders or third-party resources like social media posts.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><strong><?php _e( 'Load Event', 'strong-testimonials' ); ?>:</strong> <?php _e( 'window load', 'strong-testimonials' ); ?></p>
							</div>
						</div>

					</div><!-- .scenarios -->
				</td>
			</tr>
		</table>

		<h2><?php _e( 'Compatibility Settings', 'strong-testimonials' ); ?></h2>

		<p class="about"><?php printf( __( '<a href="%s" target="_blank">Start a support ticket</a> if you need help with these options.', 'strong-testimonials' ), esc_url( 'https://support.strongplugins.com/new-ticket/' ) ); ?></p>

		<?php
	}

	/**
	 * Page Loading
	 */
	public function settings_page_loading() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Ajax Page Loading', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p>
							<?php _e( 'This does not perform Ajax page loading.', 'strong-testimonials' ); ?>
							<?php _e( 'It provides compatibility with themes and plugins that use Ajax to load pages, also known as page animation or transition effects.', 'strong-testimonials' ); ?>
						</p>
					</div>
					<fieldset data-radio-group="prerender">
						<?php $this->settings_page_loading_none(); ?>
						<?php $this->settings_page_loading_general(); ?>
						<?php $this->settings_page_loading_advanced(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * None (default)
	 */
	public function settings_page_loading_none() {
		$checked = checked( $this->options['page_loading'], '', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="page-loading-none">
					<input id="page-loading-none"
						name="wpmtst_compat_options[page_loading]"
						type="radio"
						value=""
						<?php echo $checked; ?> />
					<?php _e( 'None', 'strong-testimonials' ); ?>
					<em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'No compatibility needed.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php _e( 'This works well for most themes.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * General
	 */
	public function settings_page_loading_general() {
		$checked = checked( $this->options['page_loading'], 'general', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="page-loading-general">
					<input id="page-loading-general"
						name="wpmtst_compat_options[page_loading]"
						type="radio"
						value="general"
						<?php echo $checked; ?> />
					<?php _e( 'General', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'Be ready to render any view at any time.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php _e( 'This works well with common Ajax methods.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Advanced
	 */
	public function settings_page_loading_advanced() {
		$checked = checked( $this->options['page_loading'], 'advanced', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="page-loading-advanced">
					<input id="page-loading-advanced"
						name="wpmtst_compat_options[page_loading]"
						data-group="advanced"
						type="radio"
						value="advanced"
						<?php echo $checked; ?> />
					<?php _e( 'Advanced', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'For specific configurations.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Prerender
	 */
	public function settings_prerender() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0" data-sub="advanced">
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Prerender', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p><?php _e( 'Load stylesheets and populate script variables up front.', 'strong-testimonials' ); ?>
							<a class="open-help-tab" href="#tab-panel-wpmtst-help-prerender"><?php _e( 'Help' ); ?></a>
						</p>
					</div>
					<fieldset data-radio-group="prerender">
						<?php $this->settings_prerender_current(); ?>
						<?php $this->settings_prerender_all(); ?>
						<?php $this->settings_prerender_none(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Current (default)
	 */
	public function settings_prerender_current() {
		$checked = checked( $this->options['prerender'], 'current', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="prerender-current">
					<input id="prerender-current"
						name="wpmtst_compat_options[prerender]"
						type="radio"
						value="current"
						<?php echo $checked; ?> />
					<?php _e( 'Current page', 'strong-testimonials' ); ?>
					<em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'For the current page only.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php _e( 'This works well for most themes.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * All
	 */
	public function settings_prerender_all() {
		$checked = checked( $this->options['prerender'], 'all', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="prerender-all">
					<input id="prerender-all"
						type="radio"
						name="wpmtst_compat_options[prerender]"
						value="all"
						<?php echo $checked; ?> />
					<?php _e( 'All views', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'For all views. Required for Ajax page loading.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php _e( 'Then select an option for <strong>Monitor</strong> below.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * None
	 */
	public function settings_prerender_none() {
		$checked = checked( $this->options['prerender'], 'none', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="prerender-none">
					<input id="prerender-none"
						type="radio"
						name="wpmtst_compat_options[prerender]"
						value="none"
						<?php echo $checked; ?> />
					<?php _e( 'None', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'When the shortcode is rendered. May result in a flash of unstyled content.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Monitor
	 */
	public function settings_monitor() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0" data-sub="advanced">
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Monitor', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p><?php _e( 'Initialize sliders, pagination and form validation as pages change.', 'strong-testimonials' ); ?></p>
					</div>
					<fieldset data-radio-group="method">
						<?php $this->settings_monitor_none(); ?>
						<?php $this->settings_monitor_universal(); ?>
						<?php $this->settings_monitor_observer(); ?>
						<?php $this->settings_monitor_event(); ?>
						<?php $this->settings_monitor_script(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * None
	 */
	public function settings_monitor_none() {
		$checked = checked( $this->options['ajax']['method'], '', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="method-none">
					<input id="method-none"
						type="radio"
						name="wpmtst_compat_options[ajax][method]"
						value=""
						<?php echo $checked; ?> />
					<?php _e( 'None', 'strong-testimonials' ); ?>
					<em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'No compatibility needed.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Universal (timer)
	 */
	public function settings_monitor_universal() {
		$checked = checked( $this->options['ajax']['method'], 'universal', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="method-universal">
					<input id="method-universal"
						name="wpmtst_compat_options[ajax][method]"
						type="radio"
						value="universal"
						data-group="universal"
						<?php echo $checked; ?> />
					<?php _e( 'Universal', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'Watch for page changes on a timer.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<div class="row" data-sub="universal">
			<div class="radio-sub">
				<label for="universal-timer">
					<?php _ex( 'Check every', 'timer setting', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<input id="universal-timer"
					name="wpmtst_compat_options[ajax][universal_timer]"
					type="number"
					min=".1" max="5" step=".1"
					value="<?php echo $this->options['ajax']['universal_timer']; ?>"
					size="3" />
				<?php _ex( 'seconds', 'timer setting', 'strong-testimonials' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Observer
	 */
	public function settings_monitor_observer() {
		$checked = checked( $this->options['ajax']['method'], 'observer', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="method-observer">
					<input id="method-observer"
						name="wpmtst_compat_options[ajax][method]"
						data-group="observer"
						type="radio"
						value="observer"
						<?php echo $checked; ?> />
					<?php _e( 'Observer', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'React to changes in specific page elements.', 'strong-testimonials' ); ?></p>
				<p class="description"><?php _e( 'For advanced users.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<?php
		/*
		 * Timer
		 */
		?>
		<div class="row" data-sub="observer">
			<div class="radio-sub">
				<label for="observer-timer">
					<?php _ex( 'Check once after', 'timer setting', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<input id="observer-timer"
					name="wpmtst_compat_options[ajax][observer_timer]"
					type="number"
					min=".1" max="5" step=".1"
					value="<?php echo $this->options['ajax']['observer_timer']; ?>"
					size="3" />
				<?php _ex( 'seconds', 'timer setting', 'strong-testimonials' ); ?>
			</div>
		</div>

		<?php
		/*
		 * Container element ID
		 */
		?>
		<div class="row" data-sub="observer">
			<div class="radio-sub">
				<label for="container-id">
					<?php _e( 'Container ID', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<span class="code input-before">#</span>
				<input class="code element"
					id="container-id"
					name="wpmtst_compat_options[ajax][container_id]"
					type="text"
					value="<?php echo $this->options['ajax']['container_id']; ?>" />
				<p class="about adjacent"><?php _e( 'the element to observe', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<?php
		/*
		 * Added node ID
		 */
		?>
		<div class="row" data-sub="observer">
			<div class="radio-sub">
				<label for="addednode-id">
					<?php _e( 'Added node ID', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<span class="code input-before">#</span>
				<input class="code element"
					id="addednode-id"
					name="wpmtst_compat_options[ajax][addednode_id]"
					type="text"
					value="<?php echo $this->options['ajax']['addednode_id']; ?>" />
				<p class="about adjacent"><?php _e( 'the element being added', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Custom event
	 */
	public function settings_monitor_event() {
		$checked = checked( $this->options['ajax']['method'], 'event', false );
		$class   = $checked ? ' class="current"' : ''; ?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="method-event">
					<input id="method-event"
						name="wpmtst_compat_options[ajax][method]"
						data-group="event"
						type="radio"
						value="event"
						<?php echo $checked; ?> />
					<?php _e( 'Custom event', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'Listen for specific events.', 'strong-testimonials' ); ?></p>
				<p class="description"><?php _e( 'For advanced users.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<div class="row" data-sub="event">
			<div class="radio-sub">
				<label for="event-name">
					<?php _e( 'Event name', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<input class="code"
					id="event-name"
					name="wpmtst_compat_options[ajax][event]"
					type="text"
					value="<?php echo $this->options['ajax']['event']; ?>"
					size="30" />
			</div>
		</div>
		<?php
	}

	/**
	 * Specific script
	 */
	public function settings_monitor_script() {
		$checked = checked( $this->options['ajax']['method'], 'script', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="method-script">
					<input id="method-script"
						name="wpmtst_compat_options[ajax][method]"
						data-group="script"
						type="radio"
						value="script"
						<?php echo $checked; ?> />
					<?php _e( 'Specific script', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'Register a callback for a specific Ajax script.', 'strong-testimonials' ); ?></p>
				<p class="description"><?php _e( 'For advanced users.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<div class="row" data-sub="script">
			<div class="radio-sub">
				<label for="script-name">
					<?php _e( 'Script name', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<select id="script-name" name="wpmtst_compat_options[ajax][script]">
					<option value="" <?php selected( $this->options['ajax']['script'], '' ); ?>>
						<?php _e( '&mdash; Select &mdash;' ); ?>
					</option>
					<option value="barba" <?php selected( $this->options['ajax']['script'], 'barba' ); ?>>
						Barba.js
					</option>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Controller
	 *
	 * @since 2.31.0
	 */
	public function settings_controller() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Load Event', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p><?php _e( 'When to start sliders, Masonry, pagination and form validation.', 'strong-testimonials' ); ?></p>
					</div>
					<fieldset>
						<?php $this->settings_page_controller_documentready(); ?>
						<?php $this->settings_page_controller_windowload(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Document ready (default)
	 */
	public function settings_page_controller_documentready() {
		$checked = checked( $this->options['controller']['initialize_on'], 'documentReady', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="controller-documentready">
					<input id="controller-documentready"
						name="wpmtst_compat_options[controller][initialize_on]"
						type="radio"
						value="documentReady"
						<?php echo $checked; ?> />
					<?php _e( 'document ready', 'strong-testimonials' ); ?>
					<em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'This works well if your page load time is less than a few seconds.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Document ready (default)
	 */
	public function settings_page_controller_windowload() {
		$checked = checked( $this->options['controller']['initialize_on'], 'windowLoad', false );
		$class   = $checked ? ' class="current"' : '';
		?>
		<div class="row">
			<div>
				<label<?php echo $class; ?> for="controller-windowload">
					<input id="controller-windowload"
						name="wpmtst_compat_options[controller][initialize_on]"
						type="radio"
						value="windowLoad"
						<?php echo $checked; ?> />
					<?php _e( 'window load', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php _e( 'Try this if your page load time is more than a few seconds.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Lazy load
	 *
	 * @since 2.31.0
	 */
	public function settings_lazyload() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Lazy Loading Images', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p><?php _e( 'Watch for lazy loading images in themes and plugins.', 'strong-testimonials' ); ?></p>
					</div>
					<fieldset>
						<?php $this->settings_page_lazyload_enabled(); ?>
						<?php $this->settings_page_lazyload_classes(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Lazy load > Enabled
	 *
	 * @since 2.31.0
	 */
	public function settings_page_lazyload_enabled() {
		$checked = checked( $this->options['lazyload']['enabled'], 1, false );
		?>
		<div class="row">
			<div>
				<label for="lazyload-enabled">
					<input id="lazyload-enabled"
						name="wpmtst_compat_options[lazyload][enabled]"
						data-group="lazyload"
						type="checkbox"
						<?php echo $checked; ?> />
					<?php _e( 'Enable watcher', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div data-sub="lazyload">
				<p class="about"><?php _e( 'Most lazy loading techniques use one or two CSS class names to indicate which images to lazy load and when the lazy loading is finished.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php _e( 'Contact support for your theme or plugin to ask if it uses CSS class names.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Lazy load > CSS classes
	 *
	 * @since 2.31.0
	 */
	public function settings_page_lazyload_classes() {
		?>
		<div class="row" data-sub="lazyload">
			<div>
				<label>
					<?php _e( 'CSS Class Names', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div class="lazyload-pairs">
				<?php
				if ( isset( $this->options['lazyload']['classes'] ) ) {
					foreach ( $this->options['lazyload']['classes'] as $key => $pair ) {
						$this->settings_page_lazyload_class_inputs( $key, $pair );
					}
				}
				?>
				<div class="pair-actions">
					<input class="button"
						id="add-pair"
						value="<?php esc_attr_e( 'Add Classes', 'strong-testimonials' ); ?>"
						type="button" />
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Lazy load > CSS classes > Individual pair
	 *
	 * @since 2.31.0
	 */
	private function settings_page_lazyload_class_inputs( $key, $pair ) {
		?>
		<div class="pair">
			<label>
				<?php _ex( 'start', 'noun', 'strong-testimonials' ); ?>
				<input class="element code"
					name="wpmtst_compat_options[lazyload][classes][<?php echo $key; ?>][start]"
					type="text"
					value="<?php echo esc_attr( $pair['start'] ); ?>" />
			</label>
			<span class="pair-sep"></span>
			<label>
				<?php _ex( 'finish', 'noun', 'strong-testimonials' ); ?>
				<input class="element code"
					name="wpmtst_compat_options[lazyload][classes][<?php echo $key; ?>][finish]"
					type="text"
					value="<?php echo esc_attr( $pair['finish'] ); ?>" />
			</label>
		</div>
		<?php
	}

	/**
	 * [Add Pair] Ajax receiver
	 */
	public function add_lazyload_pair() {
		ob_start();
		$this->settings_page_lazyload_class_inputs( $_REQUEST['key'], array( 'start' => '', 'finish' => '' ) );
		wp_send_json_success( ob_get_clean() );
	}

}

new Strong_Testimonials_Settings_Compat();
