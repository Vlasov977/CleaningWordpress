<?php
/**
 * Template Name: Home Page
 */
get_header(); ?>

<main class="template">


    <?php $image = get_the_post_thumbnail_url(); ?>
    <section class="section hero_section" <?php bg($image) ?>>
        <div class="container">
            <div class="row" >
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <?php the_content(); ?>

                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>

    <section class="section section_1">
        <div class="container">
            <div class="row">
                <h2 class="custom_title custom_title--green">
                    <?php the_field('section_title_provide'); ?>
                    <span class="lines_wrapper">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </span>
                </h2>
            </div>
            <div class="row d-flex js--checkmark">
                <?php if (have_rows('provide_block')): ?>
                    <?php while (have_rows('provide_block')) : the_row(); ?>
                        <div class="d-flex col-lg-3 col-md-4 col-sm-6 js--square service_item align-items-center"
                             data-aos="flip-left">
                            <div class=" service_item__content align-middle">

                                <?php
                                $icon = get_sub_field('block_img');
                                if (!empty($icon)):
                                    $url = $icon['url'];
                                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                                    if ($ext == 'svg'):
                                        echo file_get_contents($url);
                                    else: ?>
                                        <img src="<?php echo $url; ?>" alt="<?php echo $alt; ?>">

                                    <?php endif;
                                endif; ?>


                                <h5><?php the_sub_field('block_title'); ?></h5>
                              <span  class="description">
                                  <?php the_sub_field('description_text');?>
                              </span>

                            </div>

                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

        </div>
        <div class="justify-content-center row">
            <a href="<?php echo get_page_link(26); ?>"
               class="custom_button custom_button--green">Show Our Services</a>
        </div>



    </section>

    <section class="section section_2 section--dark"  >
        <div class="container">
            <div class="row" >
                <h2 class="custom_title custom_title--yellow">
                    <?php the_field('section_title_solution'); ?>
                    <span class="lines_wrapper">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </span>
                </h2>
            </div>
            <div class="row">
                <?php the_field('solution_content'); ?>
            </div>

            <?php $checker = get_field('show_button_solution');
            if ($checker == 1):?>
                <?php if ($link = get_field('button_solution')): ?>
                    <div class="justify-content-start row">
                        <a href="<?php echo $link['url']; ?>"
                           target="<?php echo $link['target']; ?>"
                           class="custom_button custom_button--white"><?php echo $link['title']; ?></a>
                    </div>
                <?php endif;
            endif; ?>
        </div>
    </section>

    <section class="section section_3" >
        <div class="container" >
            <div class="row">
                <h2 class="custom_title custom_title--green">
                    <?php the_field('section_title_clients'); ?>
                    <span class="lines_wrapper">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </span>
                </h2>
            </div>
            <?php if (have_rows('clients_section')): ?>
                <div class="slider section_3__slider">
                    <?php while (have_rows('clients_section')) : the_row(); ?>
                        <div class="slide">
                            <?php if ($slide = get_sub_field('logo_img')): ?>
                                <img src="<?php echo $slide['url']; ?>" alt="">
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <?php $checker = get_field('show_button_clients');
            if ($checker == 1):?>
                <?php if ($link = get_field('button_client')): ?>
                    <div class="justify-content-center row">
                        <a href="<?php echo $link['url']; ?>"
                           target="<?php echo $link['target']; ?>"
                           class="custom_button custom_button--green"><?php echo $link['title']; ?></a>
                    </div>
                <?php endif;endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>

