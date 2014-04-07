<?php
/**
 * Techfak Theme Options
 *
 * @package WordPress
 * @subpackage Techfak
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 */
function techfak_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'techfak-theme-options', get_template_directory_uri() . '/includes/theme-options.css', false, '2011-04-28' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'techfak_admin_enqueue_scripts' );

/**
 * Register the form setting for our techfak_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, techfak_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 */
function techfak_theme_options_init() {
	register_setting(
		'techfak_options',       // Options group, see settings_fields() call in theme_options_render_page()
		'techfak_theme_options', // Database option, see techfak_get_theme_options()
		'techfak_theme_options_validate' // The sanitization callback, see techfak_theme_options_validate()
	);
}
add_action( 'admin_init', 'techfak_theme_options_init' );

/**
 * Change the capability required to save the 'techfak_options' options group.
 *
 * @see techfak_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see techfak_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function techfak_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_techfak_options', 'techfak_option_page_capability' ); // option_page_capability_{$option_page}

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 */
function techfak_theme_options_add_page() {
    global $theme_page;
	$theme_page = add_theme_page(
		__( 'Theme Options', '_rrze' ),   // Name of page
		__( 'Theme Options', '_rrze' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'techfak_theme_options_render_page' // Function that renders the options page
	);

    add_action('load-'.$theme_page, 'techfak_theme_options_help_tab');

}
add_action( 'admin_menu', 'techfak_theme_options_add_page' );

function techfak_theme_options_help_tab () {
    global $theme_page;
    $screen = get_current_screen();

    if ( $screen->id != $theme_page )
        return;

	$help = '<p>' . __( 'The current theme, Techfak, provides the following Theme Options:', '_rrze' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Color Scheme</strong>: You can choose a color palette for your site.', '_rrze' ) . '</li>' .
				'<li>' . __( '<strong>Default Layout</strong>: You can choose from two available layouts.', '_rrze' ) . '</li>' .
			'</ol>';

    $screen->add_help_tab( array(
        'id' => 'techfak_theme_options',
        'title' => __( 'Theme Options', '_rrze' ),
        'content' => $help
    ) );
}


/**
 * Returns an array of color schemes registered for Techfak.
 */
function techfak_color_schemes() {
	$color_scheme_options = array(
		'blau' => array(
			'value' => 'blau',
			'label' => __( 'Blue', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-blau.png',
		),
		'graublau' => array(
			'value' => 'graublau',
			'label' => __( 'Blue-grey', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-graublau.png',
		),
		'karibikgruen' => array(
			'value' => 'karibikgruen',
			'label' => __( 'Caribic-green', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-karibikgruen.png',
		),
		'gruen' => array(
			'value' => 'gruen',
			'label' => __( 'Green', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-gruen.png',
		),
		'hellblau' => array(
			'value' => 'hellblau',
			'label' => __( 'Light-blue', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-hellblau.png',
		),
		'orange' => array(
			'value' => 'orange',
			'label' => __( 'Orange', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-orange.png',
		),
		'rot' => array(
			'value' => 'rot',
			'label' => __( 'Red', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-rot.png',
		),
		'gelb' => array(
			'value' => 'gelb',
			'label' => __( 'Yellow', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/color-scheme-gelb.png',
		),

	);

	return apply_filters( 'techfak_color_schemes', $color_scheme_options );
}

/**
 * Returns an array of layout options registered for Techfak.
 */
function techfak_layouts() {
	$layout_options = array(
		'maxwidth' => array(
			'value' => 'maxwidth',
			'label' => __( 'Max-width layout', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/layout-maxwidth.png',
		),
		'fullwidth' => array(
			'value' => 'fullwidth',
			'label' => __( 'Full-width layout', '_rrze' ),
			'thumbnail' => get_template_directory_uri() . '/includes/images/layout-fullwidth.png',
		),

	);

	return apply_filters( 'techfak_layouts', $layout_options );
}

/**
 * Returns the default options for Techfak.
 */
function techfak_get_default_theme_options() {
	$default_theme_options = array(
		'color_scheme'      => 'blau',
		'theme_layout'      => 'maxwidth'
	);

	return apply_filters( 'techfak_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for Techfak.
 */
function techfak_get_theme_options() {
	return get_option( 'techfak_theme_options', techfak_get_default_theme_options() );
}

/**
 * Returns the options array for Techfak.
 */
function techfak_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Theme Options', '_rrze' ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'techfak_options' );
				$options = techfak_get_theme_options();
			?>

			<table class="form-table">

				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Color Scheme', '_rrze' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', '_rrze' ); ?></span></legend>
						<?php
							foreach ( techfak_color_schemes() as $scheme ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="techfak_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
									<span>
										<img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="136" height="60" alt="" />
										<?php echo $scheme['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

				<tr valign="top" class="image-radio-option theme-layout"><th scope="row"><?php _e( 'Default Layout', '_rrze' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Default Layout', '_rrze' ); ?></span></legend>
						<?php
							foreach ( techfak_layouts() as $layout ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="techfak_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
									<span>
										<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
										<?php echo $layout['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see techfak_theme_options_init()
 * @todo set up Reset Options action
 */
function techfak_theme_options_validate( $input ) {
	$output = $defaults = techfak_get_default_theme_options();

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], techfak_color_schemes() ) )
		$output['color_scheme'] = $input['color_scheme'];

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], techfak_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	return apply_filters( 'techfak_theme_options_validate', $output, $input, $defaults );
}

/**
 * Enqueue all the styles for the current scheme.
 */
function techfak_enqueue_scheme() {
	$options = techfak_get_theme_options();
	$color_scheme = $options['color_scheme'];

    wp_enqueue_style( 'techfak-layout', get_template_directory_uri() . '/css/techfak/layout.css', array(), null );
    wp_enqueue_style( 'techfak-base'.$color_scheme, get_template_directory_uri() . '/css/techfak/screen/basemod_color_'.$color_scheme.'.css', array(), null );

}
add_action( 'wp_enqueue_scripts', 'techfak_enqueue_scheme' );

/**
 * Enqueue the styles for the current layout.
 */
function techfak_enqueue_layout() {
	$options = techfak_get_theme_options();
	$current_layout = $options['theme_layout'];

    wp_enqueue_style( $current_layout, get_template_directory_uri() . '/css/techfak/screen/basemod_'.$current_layout.'.css', array(), null );

}
add_action( 'wp_enqueue_scripts', 'techfak_enqueue_layout' );

/**
 * Enqueue all scripts
 */
function techfak_enqueue_scripts() {
    wp_enqueue_script( 'techfak-content', get_template_directory_uri() . '/includes/content.js', array(), false, true );
}
add_action( 'wp_enqueue_scripts', 'techfak_enqueue_scripts' );
