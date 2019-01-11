<?php
/**
 * Index
 *
 * Standard loop for the front-page
 */
get_header(); ?>

	<div class="container">
		<div class="row">
			<!-- BEGIN of main content -->
			<div class="col-md-8">
				<main class="main-content">
					<?php if (have_posts()) : ?>
						<?php while (have_posts()) : the_post(); ?>
							<?php get_template_part( 'parts/loop', 'post' ); // Post item ?>
						<?php endwhile; ?>
					<?php endif; ?>
					<!-- BEGIN of pagination -->
					<?php bootstrap_pagination(); ?>
					<!-- END of pagination -->
				</main>
			</div>
			<!-- END of main content -->
			<!-- BEGIN of sidebar -->
			<div class="col-md-4 sidebar">
				<?php get_sidebar('right'); ?>
			</div>
			<!-- END of sidebar -->
		</div>
	</div>

<?php get_footer(); ?>