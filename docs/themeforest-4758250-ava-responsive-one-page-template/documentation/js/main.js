$(document).ready(function(){
	
	var $window = $(window);
	
	$('#navigation ul.nav').affix({
      offset: {
        top: function () { return $window.width() <= 980 ? 290 : 210 }
      , bottom: 270
      }
    })

	$('#navigation ul.nav').scrollspy()

});