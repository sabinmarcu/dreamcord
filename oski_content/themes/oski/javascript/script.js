$(document).ready(function(){

 $("header .logo, section#menu .logo").click(function() {
	e = $("section#menu");
	if (!e.hasClass("active")) e.addClass("active");
	else e.removeClass("active");
    }) ;
})