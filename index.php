<?php get_header(); ?>
<!-- MENU ********************************************************************* -->
<!-- ************************************************************************** -->
<div id="main">

    <div id="menu">
      <div id="bereichsmenu">
          <h2><a name="bereichsmenumarke" id="bereichsmenumarke">Bereichsmenu</a></h2>
          <?php get_sidebar(); ?>
      </div><!-- #bereichsmenu -->

      <div id="kurzinfo">
          <div id="faulogo" class="logo">
              <p>
                  <a title="Zum Portal der Friedrich-Alexander-Universit&auml;t" href="http://www.uni-erlangen.de"><img src="<?php bloginfo('template_url') ?>/grafiken/fau.png" width="130" height="43" alt="Friedrich-Alexander - Universit&auml;t Erlangen-N&uuml;rnberg" /></a>
              </p>
          </div>
          <div id="blogslogo" class="logo">
              <p>
                  <a title="Zur Startseite der Blogs@FAU" href="/"><img src="<?php bloginfo('template_url') ?>/grafiken/blogs.png" width="120" height="81" alt="Blogs@FAU" /></a>
              </p>
          </div>
      </div><!-- #kurzinfo -->

    </div><!-- #menu -->

    <div id="content">

      <a name="contentmarke" id="contentmarke"></a>

      <?php
      if (have_posts()) :
          while (have_posts()) :
              the_post();
              $subtitle_data = array( '%author%' => get_the_author(), '%time%' => get_the_time(), '%tags%' => get_the_tag_list('', ', ', ''), '%category%' => get_the_category_list( ', ', '', false ) );
              $the_subtitle = get_option('techfak_subtitle') ? get_option('techfak_article_subtitle') : '%author%, %time% Uhr in %category%';
              foreach ($subtitle_data as $key => $value):
                  $the_subtitle = str_replace($key, $value, $the_subtitle);
              endforeach;
      ?>
      <div class="post" id="post-<?php the_ID(); ?>">
          <?php the_date('','<p class="post-date">','</p>'); ?>
          <h2 class="post-title">
              <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
          </h2>
          <p class="meta">
              <?php echo $the_subtitle; ?><?php edit_post_link(__('Edit This'), '<br/>[', ']'); ?>
          </p>      
        <?php if( has_post_format( 'gallery' ) ) : ?>
          <?php if ( post_password_required() ) : ?>
              <?php the_content( __( 'Weiterlesen <span class="meta-nav">&rarr;</span>', '_rrze' ) ); ?>

          <?php else : ?>
              <?php
              $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
              if ( $images ) :
                  $total_images = count( $images );
                  $image = array_shift( $images );
                  $image_img_tag = wp_get_attachment_image( $image->ID, 'medium', 0, array( 'class' => 'flexible bordered' ) );
                ?>
                <div class="post-content">
                  <div class="gallery-thumb">
                      <a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
                  </div>

                  <p>
                      <em><?php printf( _n( 'Diese Galerie enthält <a %1$s>%2$s Bild</a>.', 'Diese Galerie enthält <a %1$s>%2$s Bilder</a>.', $total_images, '_rrze' ),
                          'href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink zu %s', '_rrze' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
                          number_format_i18n( $total_images )
                      ); ?></em>
                  </p>
                </div>
            <?php endif; ?>
            <?php the_excerpt(); ?>
        <?php endif; ?>      
      <?php else : ?>
          <div class="post-content">
              <?php
              the_content(__('(more...)'));?>
          </div>
      <?php endif; ?>
          <p class="feedback">
              <?php wp_link_pages(); ?>
              <?php if ( comments_open() && ! post_password_required() ) : ?>
              <?php comments_popup_link(__('Comments').' (0)', __('Comments').' (1)', __('Comments').' (%)'); ?>
              <?php endif;?>
          </p>
      </div>            
      <?php
      endwhile;
          else: ?>
              <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
          <?php
          endif;
      ?>
      <div class="navigation">
              <p style="float: right;"><?php previous_posts_link('Neuere Eintr&auml;ge &raquo;') ?></p>
              <p><?php next_posts_link('&laquo; &Auml;ltere Eintr&auml;ge') ?></p>
      </div>

      <p class="noprint">
        <a href="#seitenmarke">Nach oben</a>
      </p>
      <hr id="vorfooter" />

  </div><!-- #content -->

</div><!-- /main -->    

<?php get_footer(); ?>

