
jQuery(window).load(function() {
	
	"use strict";
	
	jQuery('.page-loading').fadeOut(1000, function() {
		$(this).remove();	
	});
	
});

jQuery(document).ready(function($){
	
	"use strict";
	
	// Main navigation menu affix function
	$('.stickem-container').stickem();
	
	// Main navigation menu scrollspy to anchor section
	$('.nav-menu ul').ddscrollSpy({ scrolltopoffset: -60 });
	
	// jQuery smooth scrolling
	$('#navigation a, #logo .arrow-link a').bind('click', function(event) {
		var $anchor = $(this);	
                
                var final_anchor = $anchor.attr('href').split("#");
                
                if(final_anchor.length > 1){
                    $('html, body').stop().animate({
                            scrollTop: parseInt($("#"+final_anchor[1]).offset().top - 80, 0)
                    }, 2000,'easeInOutExpo');
                    event.preventDefault();
                }
		
	});
	
	// Top fullscreen image with jQuery backstretch
	$.backstretch('/images/bg.png');
	
	// jQuery figure hover effect
	$('figure.figure-hover').hover(
		function() { $(this).children("a").children("div").fadeIn(300); },
		function() { $(this).children("a").children("div").fadeOut(300); }
	);
	
	// Portofolio image popup gallery with Swipebox
	$("#portofolio .swipebox").swipebox();
	
	// Header text rotator with jTicker
	$("#logo .text-rotator").show().ticker({ rate: 150, delay: 3000 }).trigger("play");
	
	// Portofolio carousel animation with Flexslider
	$('#portofolio .flexslider').flexslider({
		animation: 'slide',
		directionNav: false,
		animationLoop: false,
		slideshow: false,
		start: function(slider) { $(slider).removeClass('loading'); }	
	});
	
	// Testimonials and tweets carousel animation with Flexslider
	$('.testimonials .flexslider, .tweets .flexslider').flexslider({
		directionNav: false,
		controlNav: false,
                slideshowSpeed: 10000,
		start: function(slider) { $(slider).removeClass('loading'); }	
	});
	
	// jQuery tooltips
	$('.teams .social li a').tooltip();
	
	
	/* Responsive navigation menu */
	var $navMenu	= $("#navigation .nav-menu");
	$("<select />").addClass('responsive').appendTo($navMenu);
	$("<option />", {
		"selected": "selected",
		"value"   : "#",
		"text"    : "Please select one option..."
	}).appendTo($navMenu.find('select'));
	
	// Dropdown menu list value
	$navMenu.find('ul li a').each(function() {
		var el = $(this);
		$("<option />", {
			"value"   : el.attr("href"),
			"text"    : el.text()
		}).appendTo($navMenu.find('select'));
	});
	
	// Make the drop-down work
	$navMenu.find('select').change(function() { window.location = $(this).find("option:selected").val(); });
	/* End responsive navigation menu */
	
	
	/* Twitter integration */
//	$.getJSON('includes/get-tweets.php',
//        function(feeds) {
//            // alert(feeds);
//			var displaylimit		= 5;
//			var showdirecttweets	= false;
//			var showretweets		= true;
//            var feedHTML			= '';
//            var displayCounter		= 1;
//			var $tweets				= $(".tweets .flexslider ul");
//			
//			if(feeds !== null) {
//				for (var i=0; i<feeds.length; i++) {
//					var tweetscreenname	= feeds[i].user.name;
//					var tweetusername	= feeds[i].user.screen_name;
//					var profileimage	= feeds[i].user.profile_image_url_https;
//					var status			= feeds[i].text;
//					var isaretweet		= false;
//					var isdirect		= false;
//					var tweetid			= feeds[i].id_str;
//	 
//					// If the tweet has been retweeted, get the profile pic of the tweeter
//					if (typeof feeds[i].retweeted_status !== 'undefined') {
//						profileimage	= feeds[i].retweeted_status.user.profile_image_url_https;
//						tweetscreenname	= feeds[i].retweeted_status.user.name;
//						tweetusername	= feeds[i].retweeted_status.user.screen_name;
//						tweetid			= feeds[i].retweeted_status.id_str;
//						isaretweet		= true;
//					}
//					
//					// Check to see if the tweet is a direct message
//					if (feeds[i].text.substr(0,1) === '@') {
//						isdirect = true;
//					}
//					
//					// console.log(feeds[i]);
//					
//					if (((showretweets === true) || ((isaretweet === false) && (showretweets === false))) && ((showdirecttweets === true) || ((showdirecttweets === false) && (isdirect === false)))) {
//						if ((feeds[i].text.length > 1) && (displayCounter <= displaylimit)) {
//	 
//							if (displayCounter === 1) {
//								feedHTML = '';
//							}
//							
//							feedHTML	+= '<li>';
//							feedHTML	+= '<p>';
//							feedHTML	+= '<a href="http://twitter.com/' + tweetscreenname + '/status/' + tweetid + '" target="_blank">' +  JQTWEET.timeAgo(feeds[i].created_at) + '</a>';
//							feedHTML	+= ' &mdash; ' + JQTWEET.ify.clean(status);
//							feedHTML	+= '</p>';
//							feedHTML	+= '</li>';
//							
//							displayCounter++;
//						}
//					}
//				}
//	 
//				$tweets.html(feedHTML);
//				$tweets.hide().fadeIn(1000);
//				
//				$('.tweets .flexslider').flexslider({
//					directionNav: false,
//					controlNav: false,
//					start: function(slider) { $(slider).removeClass('loading'); }	
//				});
//			}
//		}
//	);
	
	var JQTWEET = { // Twitter data format function
		timeAgo: function(dateString) { // twitter date string format function
			var rightNow = new Date();
			var then = new Date(dateString);
			
			if ($.browser.msie) {
				// IE can't parse these crazy Ruby dates
				then = Date.parse(dateString.replace(/( \+)/, ' UTC$1'));
			}
			
			var diff = rightNow - then;
			var second = 1000,
			minute = second * 60,
			hour = minute * 60,
			day = hour * 24;
	 
			if (isNaN(diff) || diff < 0) { return ""; }
			if (diff < second * 2) { return "right now"; }
			if (diff < minute) { return Math.floor(diff / second) + " seconds ago"; }
			if (diff < minute * 2) { return "1 minute ago"; }
			if (diff < hour) { return Math.floor(diff / minute) + " minutes ago"; }
			if (diff < hour * 2) { return "1 hour ago"; }
			if (diff < day) { return  Math.floor(diff / hour) + " hours ago"; }
			if (diff > day && diff < day * 2) { return "1 day ago"; }
			if (diff < day * 365) { return Math.floor(diff / day) + " days ago"; }
			else { return "over a year ago"; }
		}, // timeAgo()
		 
		ify: {
			link: function(tweet) { // twitter link string replace function
				return tweet.replace(/\b(((https*\:\/\/)|www\.)[^\"\']+?)(([!?,.\)]+)?(\s|$))/g, function(link, m1, m2, m3, m4) {
					var http = m2.match(/w/) ? 'http://' : '';
					return '<a class="twtr-hyperlink" target="_blank" href="' + http + m1 + '">' + ((m1.length > 25) ? m1.substr(0, 24) + '...' : m1) + '</a>' + m4;
				});
			},
			
			at: function(tweet) { // twitter at (@) character format function
				return tweet.replace(/\B[@＠]([a-zA-Z0-9_]{1,20})/g, function(m, username) {
					return '<a target="_blank" class="twtr-atreply" href="http://twitter.com/intent/user?screen_name=' + username + '">@' + username + '</a>';
				});
			},
			
			list: function(tweet) { // twitter list string format function
				return tweet.replace(/\B[@＠]([a-zA-Z0-9_]{1,20}\/\w+)/g, function(m, userlist) {
					return '<a target="_blank" class="twtr-atreply" href="http://twitter.com/' + userlist + '">@' + userlist + '</a>';
				});
			},
			
			hash: function(tweet) { // twitter hash (#) string format function
				return tweet.replace(/(^|\s+)#(\w+)/gi, function(m, before, hash) {
					return before + '<a target="_blank" class="twtr-hashtag" href="http://twitter.com/search?q=%23' + hash + '">#' + hash + '</a>';
				});
			},
			
			clean: function(tweet) { // twitter clean all string format function
				return this.hash(this.at(this.list(this.link(tweet))));
			}
		} // ify
	};
	/* End twitter integration */
	
	
	/* Contact us process */
	$("#contact-form").submit(function() {
		var submitData	= $(this).serialize();
		var $name		= $(this).find("input[name='name']");
		var $email		= $(this).find("input[name='email']");
		var $subject	= $(this).find("input[name='subject']");
		var $message	= $(this).find("textarea[name='message']");
		var $datastatus	= $(this).next('.data-status');
		var $submit		= $(this).find("input[name='submit']");
		
		$name.attr('disabled','disabled');
		$email.attr('disabled','disabled');
		$subject.attr('disabled','disabled');
		$message.attr('disabled','disabled');
		$datastatus.show().html('<div class="alert alert-info"><strong>Loading...</strong></div>');
		
		$.ajax({ // Send an offer process with AJAX
			type: "POST",
			url: $("#contact-form").attr("action"),
                        data: submitData,
			dataType: "json",
			success: function(data){
                            $name.val('').removeAttr('disabled');
                            $email.val('').removeAttr('disabled');
                            $subject.val('').removeAttr('disabled');
                            $message.val('').removeAttr('disabled');
                            $submit.removeAttr('disabled');
                            $datastatus.html('<div class="alert alert-info"><ul></ul></div>');
                            
                            $.each(data.msg, function(k, v){
                                $datastatus.find("ul").append("<li><b>"+v+"</b></li>");
                            });
			},
                        error: function(data){
                            $name.removeAttr('disabled');
                            $email.removeAttr('disabled');
                            $subject.removeAttr('disabled');
                            $message.removeAttr('disabled');
                            $submit.removeAttr('disabled');
                            $datastatus.html('<div class="alert alert-danger"><ul></ul></div>');
                            
                            $.each(data.msg, function(k, v){
                                $datastatus.find("ul").append("<li><b>"+v+"</b></li>");
                            });
			}
		});
		return false;
	});
	/* End contact us process */
	
	
	// jQuery placeholder for IE
	$("input, textarea").placeholder();
	
	
	/* Google map api integration with HTML */
	var googleMap = function() {
		if($('.map').length > 0) {
			$('.map').each(function(i, e) {
				var $map		= $(e);
				var $map_id		= $map.attr('id');
				var $map_title	= $map.attr('data-map-title');
				var $map_addr	= $map.attr('data-map-address');
				var $map_lat	= $map.attr('data-map-lat');
				var $map_lon	= $map.attr('data-map-lon');
				var $map_zoom	= parseInt($map.attr('data-map-zoom'), 0);
				
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
							{ hue: "#2F3238" },
							{ saturation: -20 }
						]
					}, {
						featureType: "road",
						elementType: "geometry",
						stylers: [
							{ lightness: 100 },
							{ visibility: "simplified" }
						]
					}, {
						featureType: "road",
						elementType: "labels",
						stylers: [
							{ visibility: "off" }
						]
					}
				];
				
				var styledMap	= new google.maps.StyledMapType(styles,{name: "Styled Map"});
				var map			= new google.maps.Map(document.getElementById($map_id), options);
				
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
				
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map,marker);
				});
			});
		}
	};
	
	googleMap();
	/* End Google map api integration with HTML */

});
