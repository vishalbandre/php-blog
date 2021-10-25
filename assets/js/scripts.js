/**
 * Top Bar Shrink & Expand Animation
 */
(function($) {
    "use strict";

    $(function() {
        var header = $(".start-style");
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 10) {
                header.removeClass('start-style').addClass("scroll-on");
            } else {
                header.removeClass("scroll-on").addClass('start-style');
            }
        });
    });

    //Menu On Hover

    $('body').on('mouseenter mouseleave', '.nav-item', function(e) {
        if ($(window).width() > 750) {
            var _d = $(e.target).closest('.nav-item');
            _d.addClass('show');
            setTimeout(function() {
                _d[_d.is(':hover') ? 'addClass' : 'removeClass']('show');
            }, 1);
        }
    });

})(jQuery);

/**
 * Scroll To Top Button in Footer
 */
function scrollToTop() {
    window.scrollTo(0, 0);
}

/**
 * Add Smooth Slide Down and Slide Up Effect To Bootstrap Dropdowns
 */
// Add slideDown animation to Bootstrap dropdown when expanding.
$('.dropdown').on('show.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown("fast");
});

// Add slideUp animation to Bootstrap dropdown when collapsing.
$('.dropdown').on('hide.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp("fast");
});