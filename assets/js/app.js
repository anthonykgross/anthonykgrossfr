var uri = new URI();

$(document).ready(function () {
    var resultH1 = $('#overlay .search-result h1');

    // LOADER
    $('.page-loading').fadeOut(1000, function () {
        $(this).remove();
    });

    // Overlay animation
    $("#overlay .text-rotator").show().ticker({rate: 150, delay: 10000}).trigger("play");

    // jQuery smooth scrolling
    $('.navbar-nav .nav-link, footer a, a.active-anchor').bind('click', function (event) {
        var anchor = $(this);
        var anchorId = anchor.attr('href').split("#")[1];
        animateAnchor(anchorId, event);
    });

    // POPUP FOR IMAGES
    $('.image-popup').magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom',
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function (openerElement) {
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });

    // CLIPABLE
    $('[id].clipable').each(function(i, elm) {
        var elm = $(elm);

        var i = $('<i/>')
            .addClass('fa fa-link cliper')
            .attr('data-clipboard-text', uri.origin()+uri.pathname()+'#'+elm.attr('id'))
            .attr('title', 'Copier le lien')
        ;
        elm.append(i);
    });

    var clipboard = new ClipboardJS('.cliper');
    clipboard.on('success', function() {
        new Noty({
            text: 'Merci d\'avoir copié le lien !',
            theme: 'bootstrap-v4',
            type: 'success',
            timeout: 3000
        }).show();
    });

    // CHECK HASH
    if (uri.hash().length > 0) {
        animateAnchor(uri.hash().substr(1));
    }

    // ALGOLIA
    var client = algoliasearch(algolia_app_id, algolia_api_key);
    var index = client.initIndex('pages');

    $('.navbar form input').on('keyup', function() {
        var val = $(this).val();
        var searchResult = $('#overlay .search-result');
        var searchResultChild = searchResult.find('.result');
        var banner = $('#overlay .banner');
        animateAnchor('overlay');

        resultH1.html(resultH1.attr('data-result-label'));
        searchResult.css('display', 'none');
        banner.css('display', 'block');

        if (val.length > 0) {
            searchResult.css('display', 'block');
            banner.css('display', 'none');

        }
        index.search(val, function(err, content) {
            searchResultChild.empty();
            var results = content.hits.slice(0, 4);

            if (results.length === 0) {
                resultH1.html(resultH1.attr('data-no-result-label'))
            }
            else {
                $.each(results, function (i, d) {
                    var a = $('<a/>').addClass('item col-md-3').attr('href', '/'+d.url);
                    var thumb = $('<img/>').attr('src', '/'+d.thumbnail);
                    var title = $('<div/>').addClass('title').html(d.title);
                    a.append(thumb).append(title);
                    searchResultChild.append(a);
                });
            }
        });
    })
});

function animateAnchor(anchorId, event) {
    var elm = $("[id="+anchorId+"]");

    if (elm.length >= 1) {
        changeUrl(elm.text(), uri.origin()+uri.pathname()+'#'+anchorId);
        $('html, body').stop().animate({
            scrollTop: parseInt(elm.offset().top)-100
        }, 1000);
        if(event) {
            event.preventDefault();
        }
    }
}

function changeUrl(title, url) {
    if (typeof (history.pushState) != "undefined") {
        var obj = { Title: title, Url: url };
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        console.error("Browser does not support HTML5.");
    }
}