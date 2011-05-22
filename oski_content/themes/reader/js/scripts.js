$(document).ready(function() {
	$(".switch").click(function(){
		var switchc = $(".switch");
		var dashboard = $("section#dashboard");
		var page = $("section#main");
		if (dashboard.hasClass("visible"))	{
			page
				.css({"left": "0", "position": "absolute", "display": "block", "opacity" : 0, "width": "100%"})
				.animate({"opacity": 1}, 500, 'linear', function(){
					$(this).css({"position": "relative", "width": "auto"}).removeClass("hidden").addClass("visible")
				});
			dashboard
				.css({"position": "absolute", "left" : 0, "opacity" : 1, "width": "100%"})
				.animate({"opacity": 0}, 500, 'linear', function(){
					$(this).css({"position": "relative", "display" : "none", "width": "auto"}).removeClass("visible").addClass("hidden")
				});
				
			switchc.find(".text").text("Deschide panoul de Informatii");
		} else {
			dashboard	
				.css({"left": "0", "position": "absolute", "display": "block", "opacity" : 0, "width": "100%"})
				.animate({"opacity": 1}, 500, 'linear', function(){
					$(this).css({"position": "relative", "width": "auto"}).removeClass("hidden").addClass("visible")
				});
			page
				.css({"position": "absolute", "left" : 0, "opacity" : 1, "width": "100%"})
				.animate({"opacity": 0}, 500, 'linear', function(){
					$(this).css({"position": "relative", "display" : "none", "width": "auto"}).removeClass("visible").addClass("hidden")
				});
			switchc.find(".text").text("Introarce-te la Pagina");
		}
	})
});