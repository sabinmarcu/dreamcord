red = 256;
green = 256;
blue = 256;
tint = 1;
function d2h(d) {return d.toString(16);}
function h2d(h) {return parseInt(h,16);}
$(document).ready(function() {
	setTimeout('changecolor(200, 10)', 3000);
})
function changecolor(limit, speed)	{
seed = Math.floor(Math.random()* 110);
	if (tint == 0)	{			
		if (red < 256)		red += speed;
		if (blue < 256)	blue += speed;
		if (green < 256)	green += speed;
	}   else if (tint == 1)	{
		if (red < 256)		red += speed;
		if (blue >= limit)	blue -= speed;
		if (green >= limit)	green -= speed;
	}	else if (tint == 3)	{
		if (blue < 256)		blue += speed;
		if (green >= limit)	green -= speed;
		if (red >= limit)	red -= speed;			
	}	else if (tint == 4)	{
		if (blue >= limit)		blue -= speed;
		if (green < 256)	green += speed;
		if (red < 256)	red += speed;			
	}	else if (tint == speed)	{
		if (blue < 256)		blue += speed;
		if (green >= limit)	green -= speed;
		if (red < 256)	red += speed;			
	}
	if (green >= 256 && red >= 256 && blue >= 256)	{
		if (seed % 2 == 0)	tint = 1;
		else if (seed % 3 == 0) tint = 2;
		else if (seed % 5 == 0)	tint = 3;
	}
	if (green <= limit && blue <= limit)	{if (seed % 3 == 0) tint = 0; else if (seed % 2 == 0) tint = 4; else if (seed % 5 == 0) tint = 5; else tint = 3;}
	else if (green <= limit && red <= limit)	{if (seed % 3 == 0) tint = 0; else if (seed % 2 == 0) tint = 4; else if (seed % 5 == 0) tint = 5; else tint = 1;}
	else if (green >= 256 && red >= 256 && blue <= limit)	{if (seed % 3 == 0) tint = 0; else if (seed % 2 == 0) tint = 1; else tint = 5;}
	else if (blue >= 256 && red >= 256 && green <= limit)	{if (seed % 2 == 0) tint = 0; else if (seed % 5 == 0) tint = 3; else tint = 1;}
	string = "rgb("+red+", "+green+", "+blue+")";
	$("html").css("background-color", string);		
	setTimeout('changecolor(200, 5)', 250);
}