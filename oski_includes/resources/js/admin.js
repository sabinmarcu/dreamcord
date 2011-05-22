jQuery(document).ready(function(){
if (navigator.userAgent.indexOf("Macintosh") !== -1) os = "opt";
else os = "ctrl";
jQuery(document)
  .bind("keydown", os+"+s", function(evt){
	jQuery("form#saveform").submit();
	evt.stopPropagation( );  
	evt.preventDefault( );
	return false;
}).bind("keydown", os+"+d", function(evt){
	jQuery("form#deleteform").submit();
	evt.stopPropagation( );  
	evt.preventDefault( );
	return false;
}).bind("keydown", os+"+k", function(evt){
	jQuery("form#newfileform input:first").focus();
    evt.stopPropagation( );  
    evt.preventDefault( );
    return false;
}).bind("keydown", os+"+h", function(evt){
    evt.stopPropagation();  
    evt.preventDefault();
    return false;
}).bind("keyup", os+"+h", function(evt){
	el = jQuery(".help");
	if (el.hasClass("boxed")) el.removeClass("boxed");
	else el.addClass("boxed");
    evt.stopPropagation();  
    evt.preventDefault();
    return false;
}).bind("keydown", os+"+l", function(evt){
	jQuery("form#newfolderform input:first").focus();
    evt.stopPropagation( );  
    evt.preventDefault( );
    return false;
}).bind("keydown", os+"+b", function(evt){
		document.location = jQuery("a#back").attr("href");
	    evt.stopPropagation( );  
	    evt.preventDefault( );
	    return false;
});
jQuery("a.numberLink, a.alphaLink").each(function(){
	var el = jQuery(this); 
	jQuery(document).bind("keydown", "alt+"+el.attr("id"), function(evt){
		document.location = el.attr("href");
	    evt.stopPropagation( );  
	    evt.preventDefault( );
	    return false;
	})
})
jQuery(".help").each(function(){
	var el = jQuery(this);
	el.addClass("boxed");
	el.toggle(function(){
		el.removeClass("boxed");
	}, function(){
		el.addClass("boxed");
	})
});
actionsMenu = "<div class='actions'><a id='addElem'>+</a><a id='remNav'>-</a></div>";
newNav = jQuery(".copyelem").html(); jQuery(".copyelem").remove();
newNavElem="<div class='ot'><div class='input'><input type='text' name='link' id='link' class='link' value='Type a link here'></div></div><div class='ot'><div class='input'><input type='text' name='name' id='name' class='name' value='And a name Here'></div></div>";
jQuery(".tplbase").prepend(actionsMenu);
jQuery(".navelem").find(".navcontent").prepend(actionsMenu);
jQuery(".tplelem").find(".tplcontent").prepend(actionsMenu);
jQuery(".navcontent").addClass("noTransition").sortable({ handle:".navcontent" });
jQuery("#layouts > .navelem > .navcontent > .actions a#remNav").remove();
finalNavigation = []; id = 0;
recBuild = function(elem){
	var parent = elem.attr("id");
	elem.find("> .navcontent > .navelem").each(function(elem){
		el = $(this); id = id + 1;
		el.attr('id', id);
		finalNavigation[id] = Array(el.find("input.name:first").val(), el.find("input.link:first").val(), parent);
		if (el.find(".navelem").length) recBuild(el);
	})
}
createInput = function(form, name, value)	{
	input = document.createElement("input");
	input.setAttribute("name", name);
	input.setAttribute("type", "hidden");
	form.appendChild(input); input.value = value;
}
jQuery(".actions a#addElem").live('click', function(){
	jQuery(this).parent().parent().parent().find("> .navcontent").append(newNav).find(".navelem:last > .navcontent").append(newNavElem).prepend(actionsMenu);
});
jQuery(".tplbase a#addElem").live('click', function(){
	jQuery(this).parent().parent().append(newNav).find(".tplelem:last > .tplcontent").append(actionsMenu);
})
jQuery(".actions a#remNav").live('click', function(){
	p = jQuery(this).parent().parent().parent();
	if (p.parent().attr('id') != "layouts")	p.remove();
});
jQuery(".tplbase a#remNav").live('click', function(){
	p = jQuery(this).parent().parent();
	if (p.hasClass("tplbase")) p.parent().remove();
})
jQuery("#layouts form#saveform").submit(function(){
	finalNavigation = []; var el = $("#layouts > .article.navelem");
	el.attr("id", 0);	recBuild(el);
	form = document.createElement("FORM");
	document.body.appendChild(form);
	form.method = "POST";
	form.action = document.location; i = 0;
	for(j = 1; j < finalNavigation.length; j++)	{ 
		i++;
		nav = finalNavigation[j];
		createInput(form, "name"+i, nav[0]);
		createInput(form, "link"+i, nav[1]);
		createInput(form, "parent"+i, nav[2]);
	}
	input = document.createElement("input");
	input.setAttribute("name", "update");
	input.setAttribute("type", "hidden");
	form.appendChild(input); input.value = 1;
	form.submit();
	return false;
});
jQuery(".updateResults").livequery(function(){ jQuery(this).delay(3000).css({"opacity": 1}).animate({"opacity": 0}, "slow", function(){$(this).css("display", "none")}) });
})