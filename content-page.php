<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Techfak
 */
?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<p class="meta">
				<?php edit_post_link(__('Edit This'), '[', ']'); ?>
			</p>

			<div class="post-content">
                <?php the_content(__('(more...)'));?>
			</div>

		</div>

