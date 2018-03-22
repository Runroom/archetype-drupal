import 'jquery-autogrow-textarea';

(function($, Drupal, window, document) {
    'use strict';

    Drupal.behaviors.formActions = {
        attach: function(context, settings) {
            var formInputs = $('input[type=text],[type=tel],[type=email], textarea', context);

            formInputs.on('input paste', function(event) {
                event.preventDefault();

                if ($(this).val()) {
                    $(this).addClass('focused');
                } else {
                    $(this).removeClass('focused');
                }
            });

            var textarea = $('textarea', context);
            textarea.autogrow({
                onInitialize: true
            });

            $('select', context).on('change', function() {
                if ($(this).val()) {
                    var selectedOption = $(this).find('option:selected').text();
                    $('#edit-subject-0-value').val(selectedOption);
                }
            });

            $('select option[value=_none]', context).val('');
        }
    }

    Drupal.behaviors.formOffer = {
        attach: function(context, settings) {

            // Copy subject as title
            var template = document.getElementsByClassName("offer-template");
            if(template) {
                var title = $('h1.t-h1').text();
                $('input#edit-subject-0-value').val(title);
            }

            $('.js-custom-inputfile label').on('click', function() {
                  $('input#' + $(this).attr('for')).trigger('click');
            });

            $('.js-custom-scroll').on('click',function(e) {
                e.preventDefault();

                $('html, body').stop().animate({
                    'scrollTop': $('#form-anchor').offset().top
                }, 800, 'swing');
            });
        }
    };

})(jQuery, Drupal, window, document)
