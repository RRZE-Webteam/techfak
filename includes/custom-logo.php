<?php
/**
 * Custom logo
 *
 * @package WordPress
 * @subpackage Techfak
 */

function techfak_get_default_custom_logo_options() {
	$default_custom_logo_options = array('width' => 2*MAX_LOGO_HEIGHT, 'height' => MAX_LOGO_HEIGHT);
	return apply_filters( 'techfak_get_default_custom_logo_options', $default_custom_logo_options );
}

function techfak_get_custom_logo_options() {
	return get_option( 'techfak_custom_logo_options', techfak_get_default_custom_logo_options() );
}

function techfak_custom_logo_add_page() {
    global $theme_page;
	$theme_page = add_theme_page(
		__( 'Logo', '_rrze' ),   // Name of page
		__( 'Logo', '_rrze' ),   // Label in menu
		'edit_theme_options',                     // Capability required
		'custom_logo',                          // Menu slug, used to uniquely identify the page
		'techfak_custom_logo_render_page'       // Function that renders the options page
	);

    // Adds help tab when the custom logo page loads
    add_action('load-'.$theme_page, 'techfak_custom_logo_add_help_tab');

}
add_action( 'admin_menu', 'techfak_custom_logo_add_page' );

function techfak_custom_logo_add_help_tab () {
    global $theme_page;
    $screen = get_current_screen();

    if ( $screen->id != $theme_page )
        return;

    $screen->add_help_tab( array(
        'id' => 'techfak_logo',
        'title' => __( 'Logo', '_rrze' ),
        'content' => '<p>' . __( 'You can set a custom image logo for your site. Simply upload the image and the new logo will go live immediately.', '_rrze' ) . '</p>' . '<p>' . __( 'If you want to discard your custom logo and go back to the default text, click on the button to remove the custom logo image.', '_rrze' ) . '</p>'
    ) );
}

function techfak_custom_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
        'parent' => 'appearance',
        'id' => 'custom-logo',
        'title' => __('Logo', '_rrze'),
        'href' => admin_url( 'themes.php?page=custom_logo')
    ) );
    $wp_admin_bar->add_menu( array(
        'parent' => 'appearance',
        'id' => 'theme-options',
        'title' => __('Theme Options', '_rrze'),
        'href' => admin_url( 'themes.php?page=theme_options')
    ) );

}
add_action( 'wp_before_admin_bar_render', 'techfak_custom_admin_bar_render');

function techfak_custom_logo_render_page() {
    $options = techfak_get_custom_logo_options();
	?>
	<div id="custom-logo" class="wrap">
        <div class="icon32" id="icon-themes"><br/></div>
		<h2><?php _e('Custom Logo', '_rrze')?></h2>

        <form enctype="multipart/form-data" id="upload-form" method="post" action="<?php echo esc_attr( add_query_arg( 'upload', 'custom_logo' ) ) ?>">

        <table class="form-table">
        <tbody>

        <tr valign="top">
        <th scope="row"><?php _e( 'Preview' ); ?></th>
        <td>
            <div id="logoimg" style="border: 1px solid #DFDFDF; min-height: 90px; width:<?php echo $options['width']; ?>px; height:<?php echo $options['height']; ?>px; background: url(<?php echo esc_url ( get_custom_logo_image() ) ?>) no-repeat;"> </div>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Upload Image' ); ?></th>
        <td>
            <p>
                <label for="upload"><?php _e( 'Choose an image from your computer:' ); ?></label><br />
                <input type="file" id="upload" name="import" />
                <input type="hidden" name="action" value="save" />
                <?php wp_nonce_field( 'custom-logo-options', '_wpnonce-custom-logo-options' ) ?>
                <?php submit_button( __( 'Upload' ), 'button', 'submit', false ); ?>
            </p>
        </td>
        </tr>

        <?php if ( get_custom_logo_image() ) : ?>
        <tr valign="top">
        <th scope="row"><?php _e( 'Remove Image' ); ?></th>
            <td>
                <p><?php _e( 'This will remove the logo image.', '_rrze' ) ?></p>
                <?php submit_button( __( 'Remove Logo Image', '_rrze' ), 'button', 'removelogo', false ); ?>
            </td>
        </tr>
        <?php endif;?>

        </tbody>
        </table>
        </form>

	</div>

	<?php
}

function get_custom_logo_image() {
    $url = get_theme_mod( 'custom_logo' );

	if ( 'remove-logo' == $url )
		return false;

	if ( is_ssl() )
		$url = str_replace( 'http://', 'https://', $url );
	else
		$url = str_replace( 'https://', 'http://', $url );

	return esc_url_raw( $url );
}

function custom_logo_init() {
    if ( ! current_user_can('edit_theme_options') )
        return;

    if ( empty( $_POST ) )
        return;

    if ( isset( $_POST['removelogo'] ) ) {
        check_admin_referer( 'custom-logo-options', '_wpnonce-custom-logo-options' );
        set_theme_mod( 'custom_logo', 'remove-logo' );
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }

    if ( isset( $_GET['upload'] ) ) {
        check_admin_referer('custom-logo-options', '_wpnonce-custom-logo-options');

        $overrides = array('test_form' => false);
        $file = wp_handle_upload($_FILES['import'], $overrides);

        if ( isset($file['error']) )
            wp_die( $file['error'],  __( 'Image Upload Error' ) );

        $url = $file['url'];
        $type = $file['type'];
        $file = $file['file'];
        $filename = basename($file);

        // Construct the object array
        $object = array(
        'post_title' => $filename,
        'post_content' => $url,
        'post_mime_type' => $type,
        'guid' => $url,
        'context' => 'custom-logo'
        );

        $error = '';
        teckfak_resize_image($file, '', $error);

        // Save the data
        $id = wp_insert_attachment($object, $file);

        list($width, $height, $type, $attr) = getimagesize( $file );

        $options = array('width' => $width, 'height' => $height);
        update_option('techfak_custom_logo_options', $options);

        // Add the meta-data
        wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
        update_post_meta( $id, '_wp_attachment_is_custom_logo', get_option('stylesheet' ) );

        set_theme_mod('custom_logo', esc_url($url));
        do_action('wp_create_file_in_uploads', $file, $id);

        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }

}
add_action("admin_init", "custom_logo_init");

function teckfak_resize_image($file, $new_file, &$error) {
	if(!$new_file)
		$new_file = $file;

	$info = @getimagesize($file);
	if(!$info || !$info[0] || !$info[1]){
		$error = __("Unable to get image dimensions.", '_rrze');
	}
	// from WP image.php line 22
	else if (
		!function_exists( 'imagegif' ) && $info[2] == IMAGETYPE_GIF
		||
		!function_exists( 'imagejpeg' ) && $info[2] == IMAGETYPE_JPEG
		||
		!function_exists( 'imagepng' ) && $info[2] == IMAGETYPE_PNG
	) {
		$error = __( 'Filetype not supported.', '_rrze' );
	}
	else {
		// create the initial copy from the original file
		if ( $info[2] == IMAGETYPE_GIF ) {
			$image = imagecreatefromgif( $file );
		}
		elseif ( $info[2] == IMAGETYPE_JPEG ) {
			$image = imagecreatefromjpeg( $file );
		}
		elseif ( $info[2] == IMAGETYPE_PNG ) {
			$image = imagecreatefrompng( $file );
		}
		if(!isset($image)){
			$error = __("Unrecognized image format.", '_rrze');
			return false;
		}
		if ( function_exists( 'imageantialias' ))
			imageantialias( $image, TRUE );

			$image_new_width = $image_width = $info[0];
			$image_new_height = $image_height = $info[1];

		if ( $image_height > MAX_LOGO_HEIGHT ) {
			$image_new_height = MAX_LOGO_HEIGHT;
			$image_ratio = $image_height / $image_new_height;
			$image_new_width = $image_width / $image_ratio;
		}

		$imageresized = imagecreatetruecolor( $image_new_width, $image_new_height);

        if ( $info[2] == IMAGETYPE_PNG ) {
            @imageantialias($imageresized,true);
            @imagealphablending($imageresized, false);
            @imagesavealpha($imageresized,true);

            $transparent = imagecolorallocatealpha($imageresized, 255, 255, 255, 0);
            for($x = 0; $x < $image_new_width; $x++) {
                for($y = 0; $y < $image_new_height; $y++) {
                    @imagesetpixel( $imageresized, $x, $y, $transparent );
                }
            }
            @imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );

        } else {
            @imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );
        }

		// move the thumbnail to its final destination
		if ( $info[2] == IMAGETYPE_GIF ) {
			if (!imagegif( $imageresized, $new_file ) ) {
				$error = __( "Thumbnail path invalid", '_rrze' );
			}
		}
		elseif ( $info[2] == IMAGETYPE_JPEG ) {
			if (!imagejpeg( $imageresized, $new_file, JPEG_COMPRESSION ) ) {
				$error = __( "Thumbnail path invalid", '_rrze' );
			}
		}
		elseif ( $info[2] == IMAGETYPE_PNG ) {
			if (!imagepng( $imageresized, $new_file ) ) {
				$error = __( "Thumbnail path invalid", '_rrze' );
			}
		}
	}
	if(!empty($error))
		return false;

	return true;
}
