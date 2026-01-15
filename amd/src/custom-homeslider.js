define(['jquery'], function($) {
    var defaults = {
        autoplay: false,
        interval: 5000,
        speed: 300  // Уменьшенное время для более быстрого переключения
    };

    var CustomCarousel = function(selector, options) {
        var results = $.extend(defaults, options);
        this.selector = selector;
        this.options = results;
        this.currentIndex = 0;
        this.slides = [];
        this.isPlaying = false;

        // Проверяем, существует ли элемент перед инициализацией
        if ($(this.selector).length > 0) {
            this.init();
        }
    };

    CustomCarousel.prototype.init = function() {
        var self = this;
        this.slides = $(this.selector).find('.homecarousel-slide-item');

        if (this.slides.length <= 1) {
            // Если только один слайд, просто показываем его
            this.slides.addClass('active').css('display', 'block');
            return;
        }

        // Убедимся, что первый слайд активен, но не меняем остальные
        this.slides.eq(0).addClass('active').css('display', 'block');

        // Устанавливаем навигацию
        this.setupNavigation();

        // Запускаем автопроигрывание, если включено
        if (this.options.autoplay === 'true') {
            this.startAutoplay();
        }

        // Приостанавливаем автопроигрывание при наведении
        $(this.selector).on('mouseenter', function() {
            self.pauseAutoplay();
        }).on('mouseleave', function() {
            if (self.options.autoplay === 'true') {
                self.startAutoplay();
            }
        });
    };

    CustomCarousel.prototype.setupNavigation = function() {
        var self = this;

        // Проверяем, существуют ли элементы навигации перед добавлением обработчиков
        if ($('.prevBtn').length > 0) {
            $('.prevBtn').off('click').on('click', function(e) {
                e.preventDefault();
                self.goToSlide(self.currentIndex - 1);
            });
        }

        if ($('.nextBtn').length > 0) {
            $('.nextBtn').off('click').on('click', function(e) {
                e.preventDefault();
                self.goToSlide(self.currentIndex + 1);
            });
        }
    };

    CustomCarousel.prototype.goToSlide = function(index) {
        var slideCount = this.slides.length;

        // Обрабатываем зацикливание
        if (index < 0) index = slideCount - 1;
        if (index >= slideCount) index = 0;

        // Не переключаем, если уже на нужном слайде
        if (index === this.currentIndex) {
            return;
        }

        var currentSlide = this.slides.eq(this.currentIndex);
        var nextSlide = this.slides.eq(index);

        // Выполняем переход
        this.performTransition(currentSlide, nextSlide, index);
    };

    CustomCarousel.prototype.performTransition = function(currentSlide, nextSlide, newIndex) {
        var self = this;
        var speed = this.options.speed;

        // Если анимация уже выполняется, прерываем текущую
        if (currentSlide.hasClass('sliding') || nextSlide.hasClass('sliding')) {
            // Прерываем текущую анимацию
            currentSlide.stop(true, true);
            nextSlide.stop(true, true);
        }

        // Помечаем слайды как "в процессе анимации"
        currentSlide.addClass('sliding');
        nextSlide.addClass('sliding');

        // Скрываем текущий слайд и показываем следующий
        currentSlide.removeClass('active').css('display', 'none');
        nextSlide.addClass('active').css('display', 'block');

        // Через заданное время убираем метку "в анимации"
        setTimeout(function() {
            currentSlide.removeClass('sliding');
            nextSlide.removeClass('sliding');
        }, speed);

        // Обновляем индекс
        this.currentIndex = newIndex;
    };

    CustomCarousel.prototype.nextSlide = function() {
        this.goToSlide(this.currentIndex + 1);
    };

    CustomCarousel.prototype.previousSlide = function() {
        this.goToSlide(this.currentIndex - 1);
    };

    CustomCarousel.prototype.startAutoplay = function() {
        var self = this;
        if (this.isPlaying) return;

        this.isPlaying = true;
        this.timer = setInterval(function() {
            self.nextSlide();
        }, this.options.interval);
    };

    CustomCarousel.prototype.pauseAutoplay = function() {
        if (this.timer) {
            clearInterval(this.timer);
            this.isPlaying = false;
        }
    };

    // Функция для обеспечения видимости первого слайда
    function ensureFirstSlideVisible() {
        // Убедиться, что первый слайд виден
        setTimeout(function() {
            var $firstSlide = $('.homecarousel-slide-item').first();
            if ($firstSlide.length > 0 && !$firstSlide.hasClass('active')) {
                $firstSlide.addClass('active');
            }
        }, 100);
    }

    return {
        init: function(selector, options) {
            return new CustomCarousel(selector, options);
        },
        ensureFirstSlideVisible: ensureFirstSlideVisible
    };
});