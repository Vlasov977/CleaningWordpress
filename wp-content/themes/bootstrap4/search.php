<?php
/**
 * Index
 *
 * Standard loop for the search result page
 */
get_header(); ?>

	<div class="container">
		<!-- BEGIN of search results -->
		<div class="row posts-list">
			<main class="main-content col-md-12">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'bootstrap' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
				<?php get_search_form(); ?>
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
						<?php get_template_part( 'parts/loop', 'post' ); // Post item ?>
					<?php endwhile; ?>
				<?php else: ?>
					<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'bootstrap' ); ?></p>
				<?php endif; ?>
				<!-- BEGIN of pagination -->
				<?php bootstrap_pagination(); ?>
				<!-- END of pagination -->
			</main>
		</div>
		<!-- END of search results -->
	</div>

<?php get_footer(); ?>