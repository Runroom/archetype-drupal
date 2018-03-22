(function($, Drupal, window, document) {
    'use strict';

    Drupal.behaviors.emissionBrochure = {
        attach: function(context, settings) {

            // var country = '';
            var content_allow = getCookie('content-allow');

            deleteCookie('content-allow');

            if(content_allow){
                $('.select-wrap').removeClass('show');
                $('.content').addClass('show');
            }else{

                $('.custom-residence-select .custom-select-dropdown a').on('click', function(event) {
                    event.preventDefault();
                    $('.not-allow').removeClass('show');
                    $('.custom-physically-select').removeClass('show');
                    $('.notification__confirmation').removeClass('show');
                    var access = $(this).attr('data-go-to');

                    if (access == 1) {
                        $('.custom-physically-select').addClass('show');
                    }else{
                        $('.emission-brochure .notification').removeClass('show').each(function( index ) {
                            var status = $(this).attr('data-content');
                            if (access == status) {
                                $(this).addClass('show');
                            }
                        });
                    }
                });

                $('.custom-physically-select .custom-select-dropdown a').on('click', function(event) {
                    event.preventDefault();
                    $('.notification__confirmation').removeClass('show');
                    var access = $(this).attr('data-go-to');
                    // country = $(this).text();
                    $('.emission-brochure .notification').removeClass('show').each(function( index ) {
                        var status = $(this).attr('data-content');

                        if (access == status) {
                            $(this).addClass('show');

                            if(status == 0) {
                                $('.js-notificationtext-trigger').removeClass('open');
                                $('.js-notificationtext-target').addClass('closed');
                            }
                        }
                    });
                });

                // GENERAL DISCLAIMER
                $('.general-disclaimer .btn').on('click', function(event) {
                    event.preventDefault();
                    var access = $(this).attr('data-go-to');

                    if (access == 'sec-confirmation') {
                        $('.notification__confirmation').addClass('show');
                    }else{
                        $('.emission-brochure .notification').removeClass('show').each(function() {
                            var status = $(this).attr('data-content');

                            if (access == status) {
                                $(this).addClass('show');

                                if(status == 0) {
                                    $('.js-notificationtext-trigger').removeClass('open');
                                    $('.js-notificationtext-target').addClass('closed');
                                }
                                if(status == 'content') {
                                    document.cookie = "content-allow=true";
                                    $('.select-wrap').removeClass('show');
                                }
                            }
                        });
                    }
                });

                $('.special-allow input[type="submit"]').on('click', function(event) {
                    document.cookie = "content-allow=true";
                });
            }

        }
    }


    Drupal.behaviors.viewMore = {
        attach: function(context, settings) {

            $('.js-notificationtext-trigger', context).on('click', function(event) {
                event.preventDefault();

                var $this = $(this),
                    $target = $(this).siblings('.js-notificationtext-target');

                $this.toggleClass('open');
                $target.toggleClass('closed');
                $target.height($target.find('.notification__text__content').height());
            });
        }
    }


    Drupal.behaviors.globalStyling = {
        attach: function(context, settings) {

            $('.js-hamburger').on('click', function(event) {
                event.preventDefault();
                $(this).toggleClass('is-active');
                $(this).parent().toggleClass('is-active');
                $('.Header-navMobile').toggleClass('is-open').toggleClass('is-closed');
            });

            $('.js-languagesOpener').on('click', function(event) {
                event.preventDefault();
                $(this).parent().toggleClass('open');
            });


            $('.js-closeMessageStatus').on('click', function() {
                $('.help-region .js-messageStatus').remove();
            });

            $('.help-region .js-messageStatusBox').on('click', function(event) {
                event.preventDefault();
                return false;
            })
        }
    }

    Drupal.behaviors.arrowHome = {
        attach: function(context, settings) {

            $('.Sidemenu-header', context).on('click', function(event) {
                if ($(window).width() < 768) {
                    event.preventDefault();
                    $(this).parent().toggleClass('open');
                    $('.sidemenu-main').slideToggle(300);
                }
            });

            $('.sidemenu-dropdownLabel', context).on('click', function(event) {
                $(this).toggleClass('open');
                $(this).next().slideToggle(300);
            });

            $('.js-arrow-mobile', context).on('click',function(e) {
                e.preventDefault();

                $('html, body').stop().animate({
                    'scrollTop': $('#homeHeader').offset().top + $('#homeHeader').outerHeight()
                }, 800, 'swing');
            });
        }
    }

    Drupal.behaviors.sideBar = {
        attach: function(context, settings) {

            if($('.Sidemenu', context).length > 1 && $('.Sidemenu ul ul .is-active', context).length > 1){

                var $target = $('.Sidemenu .is-active').closest('ul');

                $target.siblings('div').toggleClass('open');
                $target.slideToggle(0);
            }
        }
    }

    Drupal.behaviors.scrollAnimations = {
        attach: function(context, settings) {

            function revealOnScroll() {
                $('.anim:not(.animated)').each(function(i, obj){
                    if(($window.scrollTop() + win_height - 100) > $(obj).offset().top){
                        $(obj).addClass('animated');
                    }
                });

                $('blockquote p:not(.animated)').each(function(i, obj){
                    if(($window.scrollTop() + win_height - 100) > $(obj).offset().top){
                        setTimeout(function(){
                            $(obj).addClass('animated');
                        }, 300 * i)
                    }
                });
            }

            var $window = $(window);
            var win_height = $window.height();

            // EXECUTED ONLOAD
            revealOnScroll();

            // ONSCROLL EVENTS
            $(window, context).on('scroll', function(){
                revealOnScroll();
            });
        }
    }

    Drupal.behaviors.parallaxAnimations = {
        attach: function(context, settings) {

            function backgroundParallax(parallaxElems) {
                [].slice.call(parallaxElems).forEach(function(el,i){
                    var headerHeight = $(el).outerHeight()/2,
                    speed = 0.4,
                    windowYOffset = window.pageYOffset,
                    elBackgrounPos = "50% " + (windowYOffset * speed) + "px",
                    elOpacityVal = ($(window).scrollTop() / 700);

                    $(el).find('.parallax-veil').css('opacity', elOpacityVal);
                    el.style.backgroundPosition = elBackgrounPos;
                });
            }

            var parallax = document.querySelectorAll(".parallax");

            // EXECUTED ONLOAD
            backgroundParallax(parallax);

            // ONSCROLL EVENTS
            $(window, context).on('scroll', function(){
                backgroundParallax(parallax);
            });
        }
    }

    Drupal.behaviors.customSelect = {
        attach: function(context, settings) {

            $('.js-custom-select', context).on('click', function(event) {
                event.preventDefault();

                var $select = $(this);

                if($select.hasClass('open')) {
                    $('.custom-select-dropdown').stop(true, true).slideUp(300);
                    setTimeout(function(){
                        $select.removeClass('up down open');
                    }, 300);
                } else if($('footer').offset().top - $select.offset().top < 400) {
                    $select.find('.custom-select-dropdown').stop(true, true).slideDown(300);
                    $select.addClass('up open');
                } else {
                    $select.find('.custom-select-dropdown').stop(true, true).slideDown(300);
                    $select.addClass('down open');
                }
            });

            $('.js-custom-select-trigger', context).on('click', function(event) {
                event.stopPropagation();

                var $target = $(this);
                $target.parents('.js-custom-select').find('.header').html($target.html());

                $('.custom-select-dropdown').stop(true, true).slideUp(300);
                setTimeout(function(){
                    $target.parents('.js-custom-select').removeClass('up down open');
                }, 300);
            });

            $(document, context).click(function(event) {
                if(!$(event.target).closest('.js-custom-select').length) {
                    if($('.custom-select-dropdown').is(":visible")) {
                        $('.custom-select-dropdown').stop(true, true).slideUp(300);
                    }
                }
            });
        }
    }

    function deleteCookie(name) {
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

    function getCookie(name) {
        var match = document.cookie.match(new RegExp(name + '=([^;]+)'));
        if (match) return match[1];
    }


})(jQuery, Drupal, window, document)
