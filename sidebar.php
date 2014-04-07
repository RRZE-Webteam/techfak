<ul id="navigation">
    <li class="first">
    <?php if(is_home()): ?>
        <span class="aktiv">Startseite</span>
    <?php else: ?>
        <a href="<?php bloginfo('url') ?>/">Startseite</a>
    <?php endif; ?>
    <span class="skip">. </span>
    <ul id="hauptnavigation">
        <?php if ( is_user_logged_in() ): ?>
        <?php
            global $current_user, $current_blog;
            $dashboard_link = '';
            $user_blogs = get_blogs_of_user( $current_user->ID );
            foreach ($user_blogs AS $user_blog) {
                if( $current_blog->blog_id == $user_blog->userblog_id ) {
                    $dashboard_link = trailingslashit( get_home_url( $current_blog->blog_id ) ).'wp-admin/';
                    break;
                }
            }
        ?>
        <li id="loggedinnavigation" class="first"><span class="aktiv">Angemeldet als <span style="white-space: nowrap;"><?php echo $current_user->display_name;?></span></span><span class="skip">. </span>
            <ul class="submenu">
                <?php if( $dashboard_link ):?>
                <li class="firstchild"><a href="<?php echo $dashboard_link;?>"><?php _e('Dashboard')?></a><span class="skip">. </span></li>
                <li class="lastchild"><?php wp_loginout(); ?><span class="skip">. </span></li>
                <?php else:?>
                <li class="firstchild"><?php wp_loginout(); ?><span class="skip">. </span></li>
                <?php endif;?>
            </ul>
        </li>
        <?php else: ?>
        <li id="loggedoutnavigation" class="first"><span class="aktiv">Sie sind nicht angemeldet.</span><span class="skip">. </span>
            <ul class="submenu">
                <li class="first"><?php echo wp_loginout();?><span class="skip">. </span></li>
            </ul>
        </li>
        <?php endif; ?>
        <?php dynamic_sidebar( 1 ); ?>
    </ul>
    </li>
</ul>
