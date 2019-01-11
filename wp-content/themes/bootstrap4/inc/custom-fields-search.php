<?php
/**
 * Extend WordPress search to include custom fields
 */

/**
 * Join Postmeta table
 */
function search_join( $join ) {
	global $wpdb;

	if ( is_search() ) {
		$join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}

	return $join;
}

add_filter( 'posts_join', 'search_join' );

/**
 * Add meta_value field to where to search
 */
function search_where( $where ) {
	global $pagenow, $wpdb;
	if ( is_search() ) {
		$where = preg_replace( "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*('.*?')\s*\)/", "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}

	return $where;
}

add_filter( 'posts_where', 'search_where' );

/**
 * Prevent duplicates of posts
 */
function search_distinct( $where ) {
	global $wpdb;

	return is_search() ? 'DISTINCT' : $where;
}

add_filter( 'posts_distinct', 'search_distinct' );