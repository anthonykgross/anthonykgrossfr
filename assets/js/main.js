jQuery(window).load(function () {
    "use strict";
    jQuery('.page-loading').fadeOut(1000, function () {
        $(this).remove();
    });
});

$(document).ready(function ($) {
    "use strict";

    // Main navigation menu affix function
    $('.stickem-container').stickem();

    // jQuery smooth scrolling
    $('#navigation a, #logo .arrow-link a, #footer_logo_akg, .active-anchor').bind('click', function (event) {
        var $anchor = $(this);

        var final_anchor = $anchor.attr('href').split("#");

        if (final_anchor.length > 1) {
            var elm = $("#"+final_anchor[1]);

            if (elm.length >= 1) {
                $('html, body').stop().animate(
                    {
                        scrollTop: parseInt(elm.offset().top, 0)
                    },
                    1000
                );
                event.preventDefault();
            }
        }
    });

    // Header text rotator with jTicker
    $("#logo .text-rotator").show().ticker({rate: 150, delay: 10000}).trigger("play");

    // jQuery tooltips
    $('.teams .social li a').tooltip();


    /* Responsive navigation menu */
    var $navMenu = $("#navigation .nav-menu");
    $("<select />").addClass('responsive').appendTo($navMenu);
    $("<option />", {
        "selected": "selected",
        "value": "#",
        "text": "Please select one option..."
    }).appendTo($navMenu.find('select'));

    // Dropdown menu list value
    $navMenu.find('ul li a').each(function () {
        var el = $(this);
        $("<option />", {
            "value": el.attr("href"),
            "text": el.text()
        }).appendTo($navMenu.find('select'));
    });

    // Make the drop-down work
    $navMenu.find('select').change(function () {
        window.location = $(this).find("option:selected").val();
    });
    /* End responsive navigation menu */


    /* Contact us process */
    $("#contact-form").submit(function () {
        var submitData = $(this).serialize();
        var $name = $(this).find("input[name='name']");
        var $email = $(this).find("input[name='email']");
        var $subject = $(this).find("input[name='subject']");
        var $message = $(this).find("textarea[name='message']");
        var $datastatus = $(this).next('.data-status');
        var $submit = $(this).find("input[name='submit']");
        var $captcha = $(this).find("input[name='captcha']");

        $name.attr('disabled', 'disabled');
        $email.attr('disabled', 'disabled');
        $subject.attr('disabled', 'disabled');
        $message.attr('disabled', 'disabled');
        $captcha.attr('disabled', 'disabled');
        $datastatus.show().html('<div class="alert alert-info"><strong>Loading...</strong></div>');

        $.ajax({ // Send an offer process with AJAX
            type: "POST",
            url: $("#contact-form").attr("action"),
            data: submitData,
            dataType: "json",
            success: function (data) {
                $name.val('').removeAttr('disabled');
                $email.val('').removeAttr('disabled');
                $subject.val('').removeAttr('disabled');
                $message.val('').removeAttr('disabled');
                $captcha.val('').removeAttr('disabled', 'disabled');
                $submit.removeAttr('disabled');
                $datastatus.html('<div class="alert alert-info"><ul></ul></div>');

                $.each(data.msg, function (k, v) {
                    $datastatus.find("ul").append("<li><b>" + v + "</b></li>");
                });
            },
            error: function (d) {
                $name.removeAttr('disabled');
                $email.removeAttr('disabled');
                $subject.removeAttr('disabled');
                $message.removeAttr('disabled');
                $submit.removeAttr('disabled');
                $captcha.removeAttr('disabled');
                $datastatus.html('<div class="alert alert-danger"><ul></ul></div>');

                var data = JSON.parse(d.responseText);
                $.each(data.msg, function (k, v) {
                    $datastatus.find("ul").append("<li><b>" + v + "</b></li>");
                });
            }
        });
        return false;
    });
    /* End contact us process */


    /* Google map api integration with HTML */
    var googleMap = function () {
        if ($('.map').length > 0) {
            $('.map').each(function (i, e) {
                var $map = $(e);
                var $map_id = $map.attr('id');
                var $map_title = $map.attr('data-map-title');
                var $map_addr = $map.attr('data-map-address');
                var $map_lat = $map.attr('data-map-lat');
                var $map_lon = $map.attr('data-map-lon');
                var $map_zoom = parseInt($map.attr('data-map-zoom'), 0);

                var latlng = new google.maps.LatLng($map_lat, $map_lon);
                var options = {
                    scrollwheel: false,
                    draggable: true,
                    zoomControl: false,
                    disableDoubleClickZoom: false,
                    disableDefaultUI: true,
                    zoom: $map_zoom,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                var styles = [
                    {
                        stylers: [
                            {hue: "#2F3238"},
                            {saturation: -20}
                        ]
                    }, {
                        featureType: "road",
                        elementType: "geometry",
                        stylers: [
                            {lightness: 100},
                            {visibility: "simplified"}
                        ]
                    }, {
                        featureType: "road",
                        elementType: "labels",
                        stylers: [
                            {visibility: "off"}
                        ]
                    }
                ];

                var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});
                var map = new google.maps.Map(document.getElementById($map_id), options);

                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: $map_title
                });

                map.mapTypes.set('map_style', styledMap);
                map.setMapTypeId('map_style');

                var contentString = '<p><strong>' + $map_title + '</strong><br>' + $map_addr + '</p>';

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.open(map, marker);
                });
            });
        }
    };

    googleMap();
    /* End Google map api integration with HTML */

});