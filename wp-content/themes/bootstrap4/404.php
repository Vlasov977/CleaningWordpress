<?php
/**
 * The template for displaying 404 pages (Not Found)
 */

get_header(); ?>
	<!-- BEGIN of 404 page -->
	<div class="container-fluid">
		<div class="row text-center not-found">
			<div class="col-md-12">
				<h1><?php _e( '404: Page Not Found', 'bootstrap' ); ?></h1>
				<h3><?php _e( 'Keep on looking...', 'bootstrap' ); ?></h3>
				<p><?php printf( __( 'Double check the URL or head back to the <a class="label" href="%1s">HOMEPAGE</a>', 'bootstrap' ), get_bloginfo( 'url' ) ); ?></p>
			</div>
		</div>
	</div>
	<!-- END of 404 page -->
<?php get_footer(); ?>