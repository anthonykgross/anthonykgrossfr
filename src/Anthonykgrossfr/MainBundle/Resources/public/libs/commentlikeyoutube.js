(function(jwplayer){
   var template = function(player, config, div) {
 
      //valeurs par dÃ©faut de notre configuration
      var _config = {
         comments: []
      }
 
      function setup(evt) {
         //On fusionne la configuration par dÃ©faut avec celle envoyÃ©e par nous
         jQuery.extend(_config, config);
         createDiv();
      }
 
      //CrÃ©ation d'un calque pour afficher nos commentaires
      function createDiv(){
         jQuery("#myElement").append("<div id='commentlikeyoutube'/>");
         //On positionne notre calque en css
         jQuery("#commentlikeyoutube").css({
            height: "50px",
            width: "480px",
            display: "block",
            color: "red",
            fontWeight: "bold",
            padding: "5px",
            position: "absolute",
            top: "0px"
        });
      }
 
      function eachTime(time){
         //On vide notre calque de commentaires
         jQuery("#commentlikeyoutube").html("");
 
         /*
         * Pour chaque commentaire (comment),
         * on compare si le temps du player (time) est supÃ©rieur au temps de dÃ©but de celui en cours (min)
         * et infÃ©rieur Ã  son temps de fin (max)
         * Si c'est le cas, on l'affiche dans notre calque.
         */
         jQuery.each(_config.comments, function(k,v){
            if(time.position >= v.min && time.position <= v.max){
               jQuery("#commentlikeyoutube").html(v.comment);
            }
         });
      }
     
      //En lecture, on lance la fonction eachTime() de notre plugin
      player.onTime(eachTime);
 
      //DÃ¨s que jwplayer est prÃªt, on lance la fonction setup() de notre plugin
      player.onReady(setup);
   };
   jwplayer().registerPlugin("commentlikeyoutube", "6.0", template);
})(jwplayer);
