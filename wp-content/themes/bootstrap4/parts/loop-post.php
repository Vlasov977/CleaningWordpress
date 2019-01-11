<!-- BEGIN of Post -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row">
		<?php if (has_post_thumbnail()) : ?>
			<div class="col-md-4">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail('medium'); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="<?php echo has_post_thumbnail() ? 'col-md-8' : 'col-md-12'; ?>">
			<h3>
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s', 'bootstrap'), the_title_attribute('echo=0'))); ?>" rel="bookmark">
					<?php the_title(); ?>
				</a>
			</h3>
			<?php if (is_sticky()) : ?>
				<span class="secondary label"><?php _e('Sticky', 'bootstrap'); ?></span>
			<?php endif; ?>
			<?php the_excerpt(); // Use wp_trim_words() instead if you need specific number of words ?>
			
			<p class="entry-meta">Written by <?php the_author_posts_link(); ?> on <?php the_time(get_option('date_format')); ?></p>
		</div>
	</div>
</article>
<!-- END of Post -->