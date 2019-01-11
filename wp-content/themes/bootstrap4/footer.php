<?php
/**
 * Footer
 */
?>

<!-- BEGIN of footer -->
<footer class="footer ">
    <div class="container">
        <div class="row">

            <div class="col-md-7 col-sm-12 footer_info">
               <?php the_field('block_info', 'option') ?>

            </div>

            <div class="col-md-5  col-sm-12 ">
                <div class="custom_form custom_form--white">
                    <?php
                    $form_object = get_field('form', 'option');

                    echo do_shortcode('[gravityform id="' . $form_object['id'] . '" title="false" description="true" ajax="true"]');
                    ?>

                </div>
            </div>
        </div>



        <div class="row footer_bar">
            <?php if (have_rows('contact_phone', 'option')): ?>

                <?php while (have_rows('contact_phone', 'option')) : the_row(); ?>
                    <div class="col-lg  phone_info">
                        <?php the_sub_field('name'); ?>

                        <?php if ($phone = get_sub_field('phone')): ?>
                            <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $phone) ?>" >
                                <?php echo $phone; ?>
                            </a>
                        <?php endif; ?>
                    </div>


                <?php endwhile; ?>

            <?php endif; ?>
        </div>
    </div>
</footer>
<!-- END of footer -->

<?php wp_footer(); ?>
</body>
</html>
