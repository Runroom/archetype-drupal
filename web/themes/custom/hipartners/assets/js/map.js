(function($, Drupal, window, document) {
    'use strict';

    Drupal.behaviors.hotelMap = {
        attach: function(context, settings) {

            var map = document.getElementById('hotel-map');

            if (map) {
                var lat = map.getAttribute('data-lat'),
                    long = map.getAttribute('data-long'),
                    position = new google.maps.LatLng(lat, long);

                /* eslint-disable */
                var mapOptions = {
                    zoom: 9,
                    scrollwheel: false,
                    navigationControl: false,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    draggable: true,
                    center: position,
                    styles: [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#EDDFD3"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#F7EEE6"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#CCB29B"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#85664C"},{"lightness":0}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#FCF4ED"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]
                };
                /* eslint-enable */

                var marker = new google.maps.Marker({
                    position: position,
                    map: new google.maps.Map(map, mapOptions),
                    icon: { url: '/themes/custom/hipartners/img/marker-gmaps.png' },
                });
            }
        }
    };


})(jQuery, Drupal, window, document)
