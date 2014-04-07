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

        <?php the_post(); ?>

        <?php get_template_part( 'content', 'single' ); ?>

        <?php comments_template( '', true ); ?>

        <div class="navigation">
            <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Seiten:', '_rrze' ) . '</span>', 'after' => '</div>' ) ); ?>
        </div>

        <p class="noprint">
          <a href="#seitenmarke">Nach oben</a>
        </p>
        <hr id="vorfooter" />

    </div><!-- #content -->

</div><!-- /main -->    

<?php get_footer(); ?>
