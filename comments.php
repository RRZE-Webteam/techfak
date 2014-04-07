<div id="comments">
<?php if ( post_password_required() ) : ?>
    <p class="nopassword"><?php _e( 'Dieser Beitrag ist passwortgeschützt. Um Kommentare anschauen zu können müssen Sie das Passwort angeben..', '_rrze' ); ?></p>
</div><!-- #comments -->
<?php
	return;
endif;
?>

<?php if ( have_comments() ) : ?>
    <h3 id="comments-title"><?php
    printf( _n( 'Eine Antwort auf %2$s', '%1$s Antworten auf %2$s', get_comments_number(), '_rrze' ),
    number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
    ?></h3>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
        <div class="navigation">
            <div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Ältere Kommentare', '_rrze' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( __( 'Neuere Kommentare <span class="meta-nav">&rarr;</span>', '_rrze' ) ); ?></div>
        </div>
    <?php endif; ?>

    <ol class="commentlist">
        <?php wp_list_comments( array( 'callback' => '_rrze_list_comments' ) ); ?>
    </ol>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
        <div class="navigation">
            <div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Ältere Kommentare', '_rrze' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( __( 'Neuere Kommentare <span class="meta-nav">&rarr;</span>', '_rrze' ) ); ?></div>
        </div>
    <?php endif; ?>

<?php else : ?>
	<?php if ( ! comments_open() ) : ?>
        <p class="nocomments"><?php _e( 'Die Kommentarfunktion ist geschlossen.', '_rrze' ); ?></p>
    <?php endif; ?>

<?php endif; ?>

<?php comment_form(); ?>

</div><!-- #comments -->
