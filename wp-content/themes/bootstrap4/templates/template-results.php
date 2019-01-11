<?php/** * Template Name:  Our Results */get_header(); ?>    <main class="template template--results">        <div class="container">            <div class="row"  >                <div class="col-lg-12">                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>                        <?php the_content(); ?>                    <?php endwhile; endif; ?>                </div>            </div>            <div class="row result_wrapper"  >                <?php                $args = array(                    'post_type' => 'our_results_cpt',                    'posts_per_page' => 6);                $newpost = new WP_Query ($args);                if ($newpost->have_posts()):                while ($newpost->have_posts()): $newpost->the_post(); ?>                    <div class="col-lg-4  col-md-4  col-sm-12 result_col">                        <?php $mainImg = get_the_post_thumbnail_url(); ?>                        <div class="result  d-flex" <?php bg($mainImg) ?>>                        </div>                            <?php if (have_rows('slider')): ?>                                <div class="slider__results">                                    <?php while (have_rows('slider')) : the_row(); ?>                                        <?php if ($var = get_sub_field('slide')): ?>                                            <div class="slide">                                            <img src="<?php echo $var['url']; ?>" alt="">                                            </div>                                        <?php endif; ?>                                    <?php endwhile; ?>                                </div>                            <?php endif; ?>                    </div>                <?php endwhile; ?>            </div>            <?php if ($newpost->max_num_pages > 1) : ?>                <script>                    var ajaxurl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';                    var true_posts = '<?php echo serialize($newpost->query_vars); ?>';                    var current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;                    var max_pages = '<?php echo $newpost->max_num_pages; ?>';                </script>                <div class="justify-content-center row">                    <div id="true_loadmore" class=" custom_button custom_button--green">+ More</div>                </div>            <?php endif;            endif;            wp_reset_postdata(); ?>        </div>    </main><div class="layer"></div><!--    <div id="google-reviews"></div>--><!----><!--    <link rel="stylesheet" href="https://cdn.rawgit.com/stevenmonson/googleReviews/master/google-places.css">--><!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>--><!--    <script src="https://cdn.jsdelivr.net/gh/stevenmonson/googleReviews@6e8f0d794393ec657dab69eb1421f3a60add23ef/google-places.js"></script>--><!--    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyD8Wpm3cN7eJPNZ560M_yQvVkp8SlYN4dY&signed_in=true&libraries=places"></script>--><!----><!--    <script>--><!--        jQuery(document).ready(function( $ ) {--><!--            $("#google-reviews").googlePlaces({--><!--                placeId: 'ChIJb3wLRKO9Z4QR0_yqrOClkec' //Find placeID @: https://developers.google.com/places/place-id--><!--                , render: ['reviews']--><!--                , min_rating: 4--><!--                , max_rows:4--><!--            });--><!--        });--><!--    </script>--><?php get_footer(); ?>