<?php
add_action( 'after_setup_theme', '_rrze_setup' );

add_action( 'widgets_init', '_rrze_widgets_init' );

add_action( 'admin_enqueue_scripts', '_rrze_widget_enqueue_scripts' );

function _rrze_setup() {

	load_theme_textdomain( '_rrze', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	require( dirname( __FILE__ ) . '/includes/custom-logo.php' );

	require( dirname( __FILE__ ) . '/includes/theme-options.php' );

	require( dirname( __FILE__ ) . '/includes/widgets.php' );

	add_theme_support( 'automatic-feed-links' );

    add_theme_support( 'post-formats', array( 'gallery' ) );

	add_theme_support( 'post-thumbnails' );

    if ( ! defined( 'DIV_LOGO_HEIGHT' ) )
		define( 'DIV_LOGO_HEIGHT', 134 );

    if ( ! defined( 'MAX_LOGO_HEIGHT' ) )
		define( 'MAX_LOGO_HEIGHT', 126 );

	if ( ! defined( 'DEFAULT_LOGO_WIDTH' ) )
		define( 'DEFAULT_LOGO_WIDTH', 210 );

	if ( ! defined( 'DEFAULT_LOGO_HEIGHT' ) )
		define( 'DEFAULT_LOGO_HEIGHT', 90 );

	if ( ! defined( 'JPEG_COMPRESSION' ) )
		define( 'JPEG_COMPRESSION', 90 );

}

function _rrze_widgets_init() {

    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_RSS');

	register_widget( 'Techfak_Widget_Categories' );
	register_widget( 'Techfak_Widget_RSS' );

	register_sidebar( array(
		'name' => __( 'Menü', '_rrze' ),
		'id' => 'sidebar-1',
        'description' => __( 'Some types of widgets and up to 10 widgets are allowed.', '_rrze' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Zielgruppennavigation', '_rrze' ),
		'id' => 'sidebar-2',
		'description' => __( 'Only one custom menu is allowed.', '_rrze' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Zusatzinfo (Rechte Spalte)', '_rrze' ),
		'id' => 'sidebar-4',
		'description' => __( 'Most types of widgets and up to 10 widgets are allowed.', '_rrze' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}

function _rrze_widget_enqueue_scripts( $hook_suffix )
{
    if ( 'widgets.php' == $hook_suffix ) {
        wp_enqueue_script( 'sidebar-limit', get_bloginfo('template_directory').'/includes/sidebar.js', array(), false, true );
        wp_enqueue_style( 'sidebar-limit', get_bloginfo('template_directory').'/includes/sidebar.css' );
    }
}

function _rrze_list_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">sagt:</span>', '_rrze' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Ihr Kommentar muss noch moderiert werden.', '_rrze' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php printf( __( '%1$s um %2$s', '_rrze' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(bearbeiten)', '_rrze' ), ' ' ); ?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', '_rrze' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(bearbeiten)', '_rrze' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
