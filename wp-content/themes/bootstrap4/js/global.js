;
(function ($) {
    AOS.init({ disable: 'mobile' });


    // Fit slide video background to video holder

    function resizeVideo() {
        //var $video = $('.video');
        var $trailer = $('.videoHolder');
        $trailer.find('.video').each(function () {
            if ($trailer.width() / 16 > $trailer.height() / 9) {
                $(this).css({'width': '100%', 'height': 'auto'});
            } else {
                $(this).css({'width': 'auto', 'height': '100%'});
            }
        });

        $trailer.find('.responsive-embed').each(function () {
            if ($trailer.width() / 16 > $trailer.height() / 9) {
                $(this).css({'width': '100%', 'height': 'auto'});
            } else {
                $(this).css({'width': $trailer.height() * 16 / 9, 'height': '100%'});
            }
        });

    }


    // Sticky Footer

    var bumpIt = function () {

            $('body').css('padding-bottom', $('.footer').outerHeight(true));

            $('.footer').addClass('sticky-footer');

        },

        didResize = false;


    $(window).resize(function () {

        didResize = true;

    });

    setInterval(function () {

        if (didResize) {

            didResize = false;

            bumpIt();

        }

    }, 250);


    // Scripts which runs after DOM load


    $(document).ready(function () {




        $(".layer").hide(0);


        console.log( $('.result_col'))

        $('.result_col').each(function (i, obj) {


        });





//Remove placeholder on click

        $("input,textarea").each(function () {

            $(this).data('holder', $(this).attr('placeholder'));


            $(this).focusin(function () {

                $(this).attr('placeholder', '');

            });


            $(this).focusout(function () {

                $(this).attr('placeholder', $(this).data('holder'));

            });

        });


        $('.testimonial').each(function () {


            $titleHeight = $(this).find('.name_client').height() + 15;


            $(this).find('p').css('padding-top', $titleHeight + 'px');


        });


        if (window.matchMedia("(max-width: 570px)").matches) {

            $('.testimonial').each(function () {
                $imageHeight = $(this).find('.testimonial-image').height() + 15;
                $(this).find('.name_client').css('top', $imageHeight + 'px');
            });

        }


        $(".layer").click(function () {
            $(".slider__results").hide();
            $(".layer").hide();
        });
		
// 		if($('.result').parent().children('.slider__results').length > 0) {
// 			console.log($('.result').parent().children('.slider__results').length)
// 			$(this).addClass('hasSlider');
// 		}
		$('.result').each(function(){
			console.log();
			if ($(this).parent().children('.slider__results').length > 0) {
				$(this).addClass('hasSlider')
			}
			
		})
		
		
		

        $('.result.hasSlider').on('click', function () {
            $(".layer").show();
            $(this).parent().find('.slider__results').css('display', 'block');

            //$(this).parent().find('.slider__results').slick('unslick'); /* ONLY remove the classes and handlers added on initialize */
            //$('.slide').remove(); /* Remove current slides elements, in case that you want to show new slides. */
            $(this).parent().find('.slider__results').slick({
                dots: false,
                controlArrows: true,
                nextArrow: '<div class="custom_arrow_white custom_white_arrow--right"></div>',
                prevArrow: '<div class="custom_arrow_white custom_white_arrow--left"></div>',

                infinite: false,

                speed: 300,

                slidesToShow: 1,

                slidesToScroll: 1,
            });
            /* Initialize the slick again */

        });


        $('body').click(function (e) {
            var container = $(".show__popup").find('.slider__results');
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.fadeOut();
            }
        });


        $('.slider__results').slickLightbox({
            itemSelector: '> a'
        });


        //Make elements equal height


        $('.matchHeight').matchHeight();


        var displayedTestimonials = 4;

        vari = -1;

        varcounter = 4;

        $('.js--load-more_button').on('click', function (event) {

            event.preventDefault();


            for (item of $('.testimonial')) {


                if (i > (displayedTestimonials + 2)) {

                    displayedTestimonials = displayedTestimonials + 4;

                    if ($('.testimonial').length < i) {

                        $('.js--load-more_button').hide();

                    } else {

                        return

                    }

                } else {


                    i++;

                }


                if (i > (displayedTestimonials - 1)) {

                    if (i < (displayedTestimonials + 4)) {

                        var ourTestimonial = $('.testimonial')[counter];

                        if (displayedTestimonials <= ($('.testimonial').length + 1)) {

                            if (ourTestimonial == undefined) {

                                $('.js--load-more_button').hide();

                                return

                            } else {

                                ourTestimonial.classList.add('show');

                                $('.testimonial').each(function () {


                                    $titleHeight = $(this).find('.name_client').height() + 15;


                                    $(this).find('p').css('padding-top', $titleHeight + 'px');

                                });

                            }

                        }

                        counter++;

                    }

                }


            }


        });


        if (window.matchMedia("(max-width: 768px)").matches) {

            $('.custom-logo').addClass('nav_bar__logo').clone().appendTo(".navbar");

        }


        if (window.matchMedia("(min-width: 320px)").matches) {

            $widgetHeight = $('.js--square').width();

            $('.js--square').css('height', $widgetHeight);

            $('.js--square').css('line-height', $widgetHeight + 'px'); // For save vertival align

        }


        $('.section_3__slider').slick({

            dots: false,

            controlArrows: true,

            nextArrow: '<div class="custom_arrow custom_arrow--right"></div>',

            prevArrow: '<div class="custom_arrow custom_arrow--left"></div>',

            infinite: false,

            speed: 300,

            slidesToShow: 4,

            slidesToScroll: 4,

            responsive: [

                {

                    breakpoint: 1024,

                    settings: {

                        slidesToShow: 3,

                        slidesToScroll: 3,

                    }

                },

                {

                    breakpoint: 600,

                    settings: {

                        slidesToShow: 2,

                        slidesToScroll: 2

                    }

                },

                {

                    breakpoint: 480,

                    settings: {

                        slidesToShow: 1,

                        slidesToScroll: 1

                    }

                }

                // You can unslick at a given breakpoint now by adding:

                // settings: "unslick"

                // instead of a settings object

            ]

        });


        // Add fancybox to images

        $(".group").fancybox();

        $('.gallery-item a').attr('rel', 'gallery').attr('data-fancybox', 'gallery');

        $('a[rel*="album"], .gallery-item a, .fancybox, a[href$="jpg"], a[href$="png"], a[href$="gif"]').fancybox({

            minHeight: 0,

            helpers: {

                overlay: {

                    locked: false

                }

            }

        });


        // Sticky footer

        $('.footer').find('img').one('load', function () {

            bumpIt();

        }).each(function () {

            if (this.complete) $(this).load();

        });


        /**

         * Scroll to Gravity Form confirmation message after form submit

         */

        $(document).bind('gform_confirmation_loaded', function (event, formId) {

            var $target = $('#gform_confirmation_wrapper_' + formId);

            if ($target.length) {

                $('html, body').animate({

                    scrollTop: $target.offset().top - 25,

                }, 500);

                return false;

            }

        });


        resizeVideo();


    });


    // Scripts which runs after all elements load


    $(window).on('load', function () {



        //jQuery code goes here

        if ($('.preloader').length) {

            $('.preloader').addClass('preloader--hidden');

        }


    });


    // Scripts which runs at window resize


    $(window).resize(function () {


        if (window.matchMedia("(min-width: 320px)").matches) {

            $widgetHeight = $('.js--square').width();

            $('.js--square').css('height', $widgetHeight);

            $('.js--square').css('line-height', $widgetHeight + 'px'); // For save vertival align

        }


        $('.testimonial').each(function () {


            $titleHeight = $(this).find('.name_client').height() + 15;


            $(this).find('p').css('padding-top', $titleHeight + 'px');


        });


        if (window.matchMedia("(max-width: 570px)").matches) {

            $('.testimonial').each(function () {


                $imageHeight = $(this).find('.testimonial-image').height() + 15;


                $(this).find('.name_client').css('top', $imageHeight + 'px');


            });

        } else {

            $('.testimonial').each(function () {


                $(this).find('.name_client').css('top', 0 + 'px');


            });

        }


        resizeVideo();


    });


}(jQuery));

