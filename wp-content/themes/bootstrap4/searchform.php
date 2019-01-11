<?php
/**
 * Searchform
 *
 * Custom template for search form
 */
?>

<!-- BEGIN of search form -->
<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>" xmlns="http://www.w3.org/1999/html">
	<div class="input-group">
		<input type="search" name="s" class="form-control" id="s" placeholder="<?php _e('Search', 'bootstrap'); ?>" value="<?php echo get_search_query(); ?>"/>
		<div class="input-group-append">
			<button type="submit" class="btn btn-default" name="submit" id="searchsubmit" ><?php _e('Search', 'bootstrap'); ?></button>
		</div>
	</div>
</form>
<!-- END of search form -->