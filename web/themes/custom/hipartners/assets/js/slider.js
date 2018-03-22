import slick from 'slick-carousel-browserify';

(function($, Drupal, window, document) {
    'use strict';

    Drupal.behaviors.slider = {
        attach: function(context, settings) {
            $(document).ready(function() {
                slick($('.hotel-slider'), {
                    arrows: true
                });
            });
        }
    };

})(jQuery, Drupal, window, document)
