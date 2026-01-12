define(['jquery', 'theme_academi/slick'], function ($) {
    var defaults = {
        autoplay: false,
        interval: 5000,
        speed: 300
    };

    var SlickCarousel = function (selector, options) {
        var results = $.extend(defaults, options);
        this.selector = selector;
        this.options = results;
        this.init();
    };

    SlickCarousel.prototype.init = function () {
        var self = this;

        // Настройки для Slick слайдера
        var slickOptions = {
            infinite: true,
            speed: this.options.speed,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: this.options.autoplay === 'true',
            autoplaySpeed: this.options.interval,
            fade: true, // Используем fade для более плавного перехода
            cssEase: 'linear',
            adaptiveHeight: true,
            prevArrow: '.prevBtn',
            nextArrow: '.nextBtn',
            pauseOnHover: true,
            pauseOnFocus: true,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: true,
                        dots: false
                    }
                }
            ]
        };

        // Инициализируем Slick слайдер
        $(this.selector).slick(slickOptions);

        // Приостанавливаем автопроигрывание при наведении
        $(this.selector).on('mouseenter', function () {
            if (self.options.autoplay === 'true') {
                $(this).slick('slickPause');
            }
        }).on('mouseleave', function () {
            if (self.options.autoplay === 'true') {
                $(this).slick('slickPlay');
            }
        });
    };

    return {
        init: function (selector, options) {
            return new SlickCarousel(selector, options);
        }
    };
});