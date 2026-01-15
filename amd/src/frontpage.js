define(['jquery', 'theme_academi/slick'], function($) {
    'use strict';
    var RTL = ($('body').hasClass('dir-rtl')) ? true : false;
    return {
        init: function() {
        },
        // Available course block slider.
        availablecourses: function() {
            // Проверяем, существует ли элемент перед инициализацией слайдера
            if ($(".course-slider").length > 0) {
                $(".course-slider").slick({
                    arrows: true,
                    swipe: true,
                    infinite: false,
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    rtl: RTL,
                    responsive: [
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {

                                slidesToShow: 2,
                                slidesToScroll: 2,
                            }
                        },
                        {
                            breakpoint: 575,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        }
                    ],

                });

                var prow = $(".course-slider").attr("data-crow");
                if (prow) {
                    prow = parseInt(prow);
                    if (prow < 2) {
                        $("#available-courses .pagenav").hide();
                    }
                }
            }
        },
        // Promoted course block slider.
        promotedcourse: function() {
            // Проверяем, существует ли элемент перед инициализацией слайдера
            if ($(".promatedcourse-slider").length > 0) {
                $(".promatedcourse-slider").slick({
                    arrows: false,
                    dots: true,
                    swipe: true,
                    infinite: false,
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    rtl: RTL,
                    responsive: [
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {

                                slidesToShow: 2,
                                slidesToScroll: 2,
                            }
                        },
                        {
                            breakpoint: 575,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        }
                    ],

                });

                var prow = $(".promatedcourse-slider").attr("data-crow");
                if (prow) {
                    prow = parseInt(prow);
                    if (prow < 2) {
                        $("#promoted-courses .pagenav").hide();
                    }
                }
            }
        },
    };
});
