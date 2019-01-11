<?php
/**
 * Template Name: Price & Services
 */

get_header(); ?>

<main class="template template--price">
    <section class="catalog_section">
        <div class="container" >


            <?php if (have_rows('block_info_prices')): ?>
                <?php while (have_rows('block_info_prices')) :
                    the_row(); ?>
                    <div class="row catalog_section__row" >
                        <?php $image = get_sub_field('image_catalog') ?>
                        <div class="col-lg-5 col-sm-12 catalog_section__img""
                             data-aos-offset="300"
                             data-aos-easing="ease-in-sine"
                            <?php bg($image)?> >
                        </div>

                        <div class="col-lg-7 col-sm-12 catalog_section__content" d"
                             data-aos-offset="300"
                             data-aos-easing="ease-in-sine">
                            <div class="description">
                                <?php the_sub_field('description'); ?>
                            </div>

                            <div class="catalog_price">
                                <p><?php the_sub_field('price') ?></p>
                            </div>

                        </div>
                    </div>

                <?php endwhile; ?>
            <?php endif; ?>

        </div>

    </section>



</main>


<?php get_footer(); ?>
