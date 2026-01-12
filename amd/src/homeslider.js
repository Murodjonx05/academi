define(['jquery', 'theme_academi/jquery.sudoSlider'], function($) {
    var defaults = {
        autoplay: false,
        interval: 5000, // Default interval 5 seconds
    };

    var Carousel = function(selector, options) {
        var results = $.extend(defaults, options);
        this.initializeslider(selector, results);
    };

    // Initialize the slider with performance optimizations
    Carousel.prototype.initializeslider = function(selector, data) {
        var autostopped = false;

        // Performance-optimized slider initialization
        var sudoSlider = $(selector).sudoSlider({
            prevNext: true,
            prevHtml: '.homepage-carousel .prevBtn.carousel-control',
            nextHtml: '.homepage-carousel .nextBtn.carousel-control',
            speed: 300, // Much faster transition (was 1400)
            ease: 'swing',
            responsive: true,
            updateBefore: true,
            useCSS: true,
            interruptible: true, // Allow user interaction to interrupt animations
            numeric: false, // Disable numeric indicators for performance
            pause: (data.autoplay == 'false') ? false : data.interval,
            auto: (data.autoplay == 'true') ? true : false,
            customLink: ".homepage-carouselLink",
            afterAnimation: function(t) {
                // Highly optimized DOM manipulation with display property
                var $slides = $('.homecarousel-slide-item.carousel-item');
                $slides.removeClass('active').css('display', 'none').filter('[data-slide="' + t + '"]').addClass('active').css('display', 'block');
            },
            beforeAnimation: function() {
                // Removed heavy animation processing for better performance
            }
        });

        // Optimized event handlers with debouncing
        var timeoutId;
        sudoSlider.on('mouseenter', function() {
            clearTimeout(timeoutId);
            var auto = sudoSlider.getValue('autoAnimation');
            if (auto) {
                sudoSlider.stopAuto();
            } else {
                autostopped = true;
            }
        }).on('mouseleave', function() {
            if (!autostopped) {
                // Small delay to prevent flickering
                timeoutId = setTimeout(function() {
                    sudoSlider.startAuto();
                }, 100);
            }
        });

        // Ensure first slide is visible
        setTimeout(function() {
            $('.homecarousel-slide-item').first().addClass('active');
        }, 100);
    };

    return {
        init: function(selector, options) {
            return new Carousel(selector, options);
        }
    };
});
