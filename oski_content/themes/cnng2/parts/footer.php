	</div>
	<div class="sidebar right">
		<div class="widget" id='logo'>
			<a href="/"><div class="logo"></div></a>
		</div>
	</div>
	<div class='fix'></div>
</div>
<div class='footer small'>Site generat cu <a href='http://www.oskiengine.co.cc/'> OSKI ENGINE </a> &copy; M&MTek 2010 - <?php echo date("Y", time())?></div>
</div>
<?php noTDetails() ?>
<script type="text/javascript" charset="utf-8">
	$(".navigation.main").css({"margin-top": "-45px", "padding-bottom": "45px"}).delay(750).animate({"margin-top": 0, "padding-bottom": 0}, 250);
	$(".sidebar.left .colors").css({"margin-left": "-175px", "padding-right": "175px"}).delay(750).animate({"margin-left": 0, "padding-right": 0}, 250);
	$(".sidebar.left .widget").css({"margin-left": "-175px", "padding-right": "175px"}).delay(750).animate({"margin-left": 0, "padding-right": 0}, 250);
	$(".sidebar.right .widget").css({"margin-right": "-175px", "padding-left": "175px"}).delay(750).animate({"margin-right": 0, "padding-left": 0}, 250);

	$(".section.content").removeClass("noJS");

	$(".navigation.sec ul > ul").hide();
	$(".navigation.sec ul > li a").click(function(e){
		e.preventDefault();
		li = $(this).parent();
		ul = li.find("ul:first"); cond = ul.css("display");
		li.parent().find("ul").hide();
		if (ul.length)	{
			if (cond == "none")	ul.show();
			else ul.hide();
		}
		else window.location = $(this).attr("href");
	});
</script>