(function($) {
    $.fn.navbarToggle = function() {
        return this.each(function() {
            var target = $(this).data('target');
            $(this).on('click', function() {
                $(target).toggleClass('navbar-visible');
            });   
        });
    };
})(jQuery);

jQuery(function($) {
    
    $('.post-gallery .carousel').owlCarousel({
            items: 4,
            margin: 5,
            nav: true,
            navText: ['<span><span>next</span></span>', '<span><span>prev</span></span>'],
            responsive: {
                1300: {
                    items: 5
                },
                2000: {
                    items: 6
                }
            }
        }).find('.thumbnail').on('click', function(e) {
            e.preventDefault();
            var $mainImage = $(this).closest('.post-gallery').find('.main-image');
            $mainImage.find('img').attr('src', $(this).data('largeImage'));
            $mainImage.find('a')
                .attr('href', $(this).attr('href'));
            return false;
        });
        $('.post-gallery a')
            .attr('rel', 'fancy-gallery')
            .fancybox();
    
    $('.navbar-toggle').navbarToggle();        
    var $toggleButton = $('<button class="toggle-subnav"><span>toggle subnavigation</span></button>');
    $toggleButton.on('click', function() {
        $(this).closest('.parent').find('> ul').toggleClass('visible');
    });        
    $('#nav .parent').append($toggleButton);    
    
    $('.products-list img').unveil(200, function() {
        $(this).load(function() {
            $(this).addClass('unveiled');
        });
    }); 
    
    $('.facebook-share').on('click', function(e) {
        e.preventDefault();
        window.open(
            $(this).attr('href'),
            'facebook-share',
            'height=320, width=640, toolbar=no menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'
        );
    });
});
