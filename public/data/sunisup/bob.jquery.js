(function(a){a.sunisup=function(d,e){var f={value:3,data:[{min:0,max:133,bColor:"#0C500F",fColor:"#720404"},{min:133,max:266,bColor:"#720404",fColor:"#6B6416"},{min:266,max:400,bColor:"#6B6416",fColor:"#0C500F"}]},b=this;a(d);b.options={};b.init=function(){b.options=a.extend({},f,e);var g=b.options,h=b.options.value;obj=null;a.each(b.options.data,function(b,a){obj=a});g.value=h%obj.max;obj=null;a.each(b.options.data,function(a,c){b.options.value>=c.min&&b.options.value<=c.max&&(obj=c)});data=obj;
null!=data&&(heureDep=data.min,colorDep=data.bColor,heureFin=data.max,colorFin=data.fColor,d_color_r=parseInt(c(colorDep).substring(0,2),16),d_color_g=parseInt(c(colorDep).substring(2,4),16),d_color_b=parseInt(c(colorDep).substring(4,6),16),f_color_r=parseInt(c(colorFin).substring(0,2),16),f_color_g=parseInt(c(colorFin).substring(2,4),16),f_color_b=parseInt(c(colorFin).substring(4,6),16),ecart_color_r=f_color_r-d_color_r,base_color_r=d_color_r,ecart_color_g=f_color_g-d_color_g,base_color_g=d_color_g,
ecart_color_b=f_color_b-d_color_b,base_color_b=d_color_b,coefHeure=1-(heureFin-b.options.value)/(heureFin-heureDep),coef_r=ecart_color_r*coefHeure,coef_g=ecart_color_g*coefHeure,coef_b=ecart_color_b*coefHeure,final_color_r=Math.round(base_color_r+coef_r),final_color_g=Math.round(base_color_g+coef_g),final_color_b=Math.round(base_color_b+coef_b),0>final_color_r&&(final_color_r=0),0>final_color_g&&(final_color_g=0),0>final_color_b&&(final_color_b=0),a(d).css("background-color","rgb("+final_color_r+
","+final_color_g+","+final_color_b+")"))};var c=function(a){return"#"==a.charAt(0)?a.substring(1,7):a};b.init()};a.fn.sunisup=function(d){return this.each(function(){var e=new a.sunisup(this,d);a(this).data("sunisup",e)})}})(jQuery);