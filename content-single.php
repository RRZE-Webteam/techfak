<?php
$subtitle_data = array( '%author%' => get_the_author(), '%time%' => get_the_time(), '%tags%' => get_the_tag_list('', ', ', ''), '%category%' => get_the_category_list( ', ', '', false ) );
$the_subtitle = get_option('techfak_subtitle') ? get_option('techfak_article_subtitle') : '%author%, %time% Uhr in %category%';
foreach ($subtitle_data as $key => $value):
    $the_subtitle = str_replace($key, $value, $the_subtitle);
endforeach;
?>

<div class="post" id="post-<?php the_ID(); ?>">
    <?php if( $post->ID > 0 ):?>
    <?php the_date('','<p class="post-date">','</p>'); ?>

    <p class="meta">
        <?php echo $the_subtitle; ?><?php edit_post_link(__('Edit This'), '<br/>[', ']'); ?>
    </p>
    <?php endif;?>

    <div class="post-content">
        <?php the_content(__('(more...)'));?>
    </div>

</div>
