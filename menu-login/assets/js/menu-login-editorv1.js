jQuery(document).ready(function($) {

    jQuery(document).on('click', '#pre-show-popup-btn-menu-ajaxlogin-popup', function(e) {
        e.preventDefault();

        // Access the iframe's content
        var iframeContent = $('#elementor-preview-iframe').contents();
        
        // Show all popups with the class "menu-login-ajax-popup-gpt" inside the iframe
        iframeContent.find('.menu-login-ajax-popup-gpt').css('display', 'block');

        // For each child div of .menu-login-ajax-popup-gpt, set position, top, and left styles
        iframeContent.find('.menu-login-ajax-popup-gpt > div').each(function() {
            // Get the container width of the child div
            var containerWidth = $(this).outerWidth();
            // Calculate left value using CSS calc()
            var leftValue = "calc(50% - " + (containerWidth / 2) + "px)";
            $(this).css({
                'position': 'absolute',
                'top': '50px',
                'left': leftValue
            });
        });
    });
});
