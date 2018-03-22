(function($, Drupal, window, document) {
    'use strict';

    Drupal.behaviors.youtubeLoader = {
        attach: function(context, settings) {
            var v = document.getElementsByClassName("youtube-player");
            for (var n = 0; n < v.length; n++) {
                var p = document.createElement("div");
                p.innerHTML = labnolThumb(v[n].dataset.id, v[n].dataset.cover);
                p.onclick = labnolIframe;
                v[n].appendChild(p);
            }

            function labnolThumb(id, cover) {
                if (cover) {
                    return '<img class="youtube-thumb" alt="youtube video" src="'
                        + cover + '"><div class="play-button"></div>';
                }
                return '<img class="youtube-thumb" src="//i.ytimg.com/vi/'
                    + id + '/hqdefault.jpg"><div class="play-button"></div>';
            }

            function labnolIframe() {
                var iframe = document.createElement("iframe");
                iframe.setAttribute("src", "//www.youtube.com/embed/"
                    + this.parentNode.dataset.id
                    + "?autoplay=1&autohide=2&border=0&wmode=opaque&enablejsapi=1");
                iframe.setAttribute("frameborder", "0");
                iframe.setAttribute("id", "youtube-iframe");
                this.parentNode.replaceChild(iframe, this);
            }
        }
    }

})(jQuery, Drupal, window, document)
