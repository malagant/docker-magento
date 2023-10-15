jQuery(function($) {

    var matchHeight;
    var applyTabs;

    $.fn.verticalAlign = function () {
        return this.each(function() {
            var parent = $(this).parent();
            var padding = parent.outerHeight() - $(this).outerHeight();
            parent.css(
                'padding-top',
                (padding / 2) + 'px'
            );
        });
    };

    $.fn.verticalAlignMargin = function () {
        return this.each(function() {
            var parent = $(this).parent();
            var margin = $(this).height() / 2;
            if (parent.height() > margin) {
                $(this).css('margin-top', -(margin));
            }
        });
    };

    $(window).on('resize', function() {
        $('.box-title').not('.product-view-top .box-title, .teaser-top .box-title').verticalAlign();
        $('.teaser-inner').verticalAlignMargin();
    }).trigger('resize');



    /**
     * catalog filter
     * 
     * @returns {undefined}
     */
    (function() {
        $('body').on('mouseenter', '.block-layered-nav .filter-item', function(e) {
            $(this).find('.m-filter-item-list').addClass('active');
        }).on('mouseleave', '.block-layered-nav .filter-item', function(e) {
            $(this).find('.m-filter-item-list').removeClass('active');
        });
    })();

   (function() {
        var $allAnswers = $('.faq-answer').hide();
        var $allQuestions = $('.faq-question');
        $allQuestions.on('click', function() {
            $allQuestions.not($(this)).removeClass('open');
            $(this).toggleClass('open');
            var $target = $(this).next();
            $allAnswers.not($target).stop(true, true).slideUp();
            if ($(this).hasClass('open')) {
                $target.slideDown();
            } else {
                $target.slideUp();
            }
        });
    })();

    $('.quick-access .quick-search label').on('click', function(e) {
        $(this).closest('.quick-search').toggleClass('active');
    });
    $(document).on('click', function (e) {
        var $container = $('.quick-search');
        if (!$container.is(e.target) &&
            $container.has(e.target).length === 0
        ) {
            $('.quick-search').removeClass('active');
        }
    });

    /**
     * Archive toggle
     *
     * @returns {undefined}
     */
    (function() {

       $('body').on('click','.block-blog-archives .block-title', function (e) {
           $('.block-blog-archives .block-content').slideToggle();
       });

    })();

    /**
     * post galleries
     *
     * @returns {undefined}
     */
    (function() {
        var $slider = $('.post-gallery .main-image');
        $slider.addClass('owl-carousel owl-theme').owlCarousel({
            items: 1,
            nav: true,
            autoHeight: true,
            navText: ['<span class="previous"><span>prev</span></span>', '<span class="next"><span>next</span></span>']
        });
        $('.post-gallery .carousel').find('.thumbnail').on('click', function(e) {
            e.preventDefault();
            $slider.trigger('to.owl.carousel', [$(this).index(), 300, true]);
        });
    })();
    
    (function() {

        matchHeight = function() {
            $('[data-mh]').matchHeight();
            $('img').on('load', function() {
                $('[data-mh]').matchHeight();
            });
        };
        $(document).ajaxComplete(matchHeight);
        matchHeight();
    })();

    /**
     * fancybox view post
     *
     * @returns {undefined}
     */
    (function() {
        $('[data-ajax-href]').each(function() {
            $(this).attr('href', $(this).data('ajaxHref'));
            $(this).fancybox({
                type: 'ajax',
                maxWidth: '800'
            });
        });
        $('[data-fancybox-type]').fancybox();
        $('.post-gallery .main-image a').attr('rel', 'mr-gallery').fancybox();
    })();

    /**
     *
     * @returns {undefined}
     */
    (function() {
        var productGallery = function() {
            $('.product-preview-image a').each(function() {
                $(this).attr('rel', $(this).data('rel'));
            }).fancybox();
            $('.detail-images').addClass('owl-carousel owl-theme').owlCarousel({
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 3,
                        autoWidth: true
                    }
                },
                nav: true,
                navText: ['<span class="previous"><span>prev</span></span>', '<span class="next"><span>next</span></span>']
            });
        };


        $(document).ajaxSuccess(productGallery);
        $('.tabs').on('tabsactivate', function( event, ui ) {
            if (ui.newPanel.attr('id') === 'product-details') {
                productGallery();
            }
        } );
        $(document).on('fragment.reloader.done', productGallery);
    })();

    /**
     * Main slider
     *
     * @returns {undefined}
     */
    (function() {
        $('.slider').addClass('owl-carousel owl-theme').owlCarousel({
            items: 1,
            nav: true,
            loop: true,
            navText: ['<span class="previous"><span>prev</span></span>', '<span class="next"><span>next</span></span>']
        });
    })();

    /**
     * fragmentLoader loads specific blocks via ajax and inserts them
     * into the layout

     * @returns {undefined}
     */
    (function() {

        var fragmentLoaderList = [
            '.download-pager a',
            '.skylopedia-filter a',
            '.block-layered-nav a',
            '.category-products-grid .toolbar a',
            '.news-list-pager a'
        ];
        var selector = fragmentLoaderList.join(', ');

        $(document).fragmentLoader(selector, {
            fragments: ['content'],
            beforeSend: function() {
                $('<div class="loading-mask"><div class="spinner"></div></div>').appendTo($('body'));
            },
            success: function(xhr) {
                $('.loading-mask').remove();                
                $('body').animate({
                        scrollTop: $('.main-container').offset().top
                    },
                    300, 
                    'linear',
                    function() {
                        $(document).trigger('fragment.reloader.done');
                    }
                );
            }
        });
    })();

    /**
     * Scrollable subnavigation
     *
     * @returns {undefined}
     */
    (function() {
        var $subnav = $('.subnavigation');
        $subnav.carouFredSel({
            infinite: false,
            circular: false,
            next: '#subnav-next',
            prev: '#subnav-prev',
            scroll: {
                items: 1
            },
            align: 'left',
            auto: false,            
            width: "100%"
        });

        var $active = $subnav.find('.active');
        if ($active.length) {
           $subnav.trigger('slideTo', [$active.index(), {
                duration: 10
           }]);
        }

    })();

    /**
     * Main tabs
     *
     * @returns {undefined}
     */
    (function() {
        applyTabs = function() {
            var $tabs = $('.tabs').tabs({
                show: 'fade',
                hide: 'fade',
                activate: function (event, ui) {
                    window.location.hash = '!' + ui.newPanel.attr('id');
                }
            });

            var selectTab = function () {
                var tab = window.location.hash.replace('!', '');
                $('a[href="' + tab + '"]').trigger('click');
            };

            $(window).on('hashchange', selectTab).trigger('hashchange');

        };
        applyTabs();
        $(document).on('fragment.reloader.done', applyTabs);
    })();

    /**
     * Reload product details for configurable product
     *
     * @returns {undefined}
     */
    (function() {
        $('#product_addtocart_form')
        .on('configurable.load.simple.before', function() {
            $('<div class="loading-mask"><div class="spinner"></div></div>').appendTo($('body'));
        })
        .on('configurable.load.simple', function(e, data) {
            for (var selector in data) {
                $(selector).html($(data[selector]));
                $('.validation-advice').slideUp();
                $('.loading-mask').remove();
            }
        });
    })();

    (function() {
        $('[name="update_cart_action"]').on('click', function (e) {
            $('.cart form').submit();
        });
    })();

    /**
     *  Nav toggle
     *
     * @returns {undefined}
     */
    (function() {
        $('.toggle-nav').on('click', function(e) {
            $('body').toggleClass('menu-open');
        });

        var $submenuToggle = $('<button type="button" class="submenu-toggle"><span>+</span></button>')
            .on('click', function (e) {
                $(this).toggleClass('toggle-open');
                $(this).closest('.parent').find('> ul').toggleClass(
                    'submenu-open',
                    $(this).hasClass('toggle-open')
                );
            }
        );

        $('.navigation').find('.parent')
            .append($submenuToggle);

    })();

    (function (window, Modernizr) {

        var applyZoom = function () {
            $('#image-main').elevateZoom();
        };

        var removeZoom = function () {
            $('#image-main').removeData('elevateZoom');
            $('.zoomWrapper img.zoomed').unwrap();
            $('.zoomContainer').remove();
        };

        if (Modernizr.mq('only all')) {
            $(window).on('resize', function () {
                if (Modernizr.mq('(max-width: 768px)')) {
                    removeZoom();
                } else {
                    applyZoom();
                }
            }).trigger('resize');
        } else {
            applyZoom();
        }
    })(window, Modernizr);

    (function () {
        var applyHoverDir = function() {
            $('.products-grid .item').each(function() {
                $(this).hoverdir({
                    hoverDelay : 75,
                    target: '.product-details'
                });
            });
        };
        applyHoverDir();
        $(document).on('fragment.reloader.done', applyHoverDir);
    })();

});
