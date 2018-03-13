$(document).ready(function () {
    // LOADER
    $('.page-loading').fadeOut(1000, function () {
        $(this).remove();
    });

    // Overlay animation
    $("#overlay .text-rotator").show().ticker({rate: 150, delay: 10000}).trigger("play");

    // jQuery smooth scrolling
    $('.navbar-nav .nav-link, footer a').bind('click', function (event) {
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

    $.ajax({
        url: "/captcha",
        success: function (d) {
            $('input[name="captcha_id"]').val(d.idx);
            $('input[name="captcha"]').attr("placeholder", "Captcha : "+d.question);
        }
    });

    /* Contact us process */
    $("#contact form").submit(function () {
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
            url: $(this).attr("action"),
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
                $datastatus.html('<div data-anchor-id="alert-danger" class="alert alert-danger"><ul></ul></div>');

                var data = JSON.parse(d.responseText);
                $.each(data.msg, function (k, v) {
                    $datastatus.find("ul").append("<li><b>" + v + "</b></li>");
                });
                animateAnchor('alert-danger');
            }
        });
        return false;
    });
});

function animateAnchor(anchorId, event) {
    var elm = $("[data-anchor-id="+anchorId+"]");

    if (elm.length >= 1) {
        $('html, body').stop().animate({
            scrollTop: parseInt(elm.offset().top)-100
        }, 1000);
        if(event) {
            event.preventDefault();
        }
    }
}