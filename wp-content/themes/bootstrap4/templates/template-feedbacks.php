<?php
/**
 * Template Name: Feedbacks
 */

get_header(); ?>

    <main class="template template--testimonials">
        <div class="container">
            <div class="row">

                <div class="testimonials" >
                    <?php echo do_shortcode("[testimonial_view id=\"1\"]" ); ?>
                </div>
                <a href="#" class="custom_button custom_button--green js--load-more_button" >+More</a>
            </div>


            <div class="row">
                <div class="testimonials__form" >
                    <?php echo do_shortcode("[testimonial_view id='2']"); ?>
                </div>
            </div>
        </div>

    </main>

<?php get_footer(); ?>