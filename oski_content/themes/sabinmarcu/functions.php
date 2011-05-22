<?php
	date_default_timezone_set("Europe/Bucharest");
	function get_date()	{
		$day = date("j", time());
		$name = date("l", time());
		$month = date("F", time());
		$year = date("Y", time());
		echo dtoro($name), ", ", $day, " ", mtoro($month), " ", $year;
	}
	function get_time()	{
		$hour = date("G", time());
		$minutes = date("i", time());
		if (strpos("0", $minutes) === 0)	$minutes = substr($minutes, 1, 1);
		echo "<span id='hour'>", $hour, "</span>:<span id='minute'>", $minutes, "</span><script type='text/javascript'> 
		function updatetime() { 
			hour =  document.getElementById(\"hour\");
			minute =  document.getElementById(\"minute\");
			time =  minute.innerHTML; 
			if (parseInt(time) >= 60)	{
				minute.innerHTML = \"00\";
				hours = parseInt(hour.innerHtml);
				if (hours + 1 == 24)	location.reload(true);
				else hour.innerHTML = hours + 1;
			}	else minute.innerHTML = parseInt(time) + 1;
			setTimeout(\"updatetime()\", 64000);
		}; setTimeout(\"updatetime()\", 64000); </script>";
	}
	function dtoro($day)	{
		switch ($day)	{
			case "Monday" : return "Luni";
			case "Tuesday" : return "Marti";
			case "Wednesday" : return "Miercuri";
			case "Thursday" : return "Joi";
			case "Friday" : return "Vineri";
			case "Saturday" : return "Sambata";
			case "Sunday" : return "Duminica";
		}
	}
	function mtoro($month)	{
		switch ($month)	{
			case "January" : return "Ianuarie";
			case "February" : return "Februarie";
			case "March" : return "Martie";
			case "April" : return "Aprilie";
			case "May" : return "Mai";
			case "June" : return "Iunie";
			case "July" : return "Iulie";
			case "August" : return "August";
			case "September" : return "Septembrie";
			case "October" : return "Octombrie";
			case "November" : return "Noiembrie";
			case "December" : return "Decembrie";
		}
	}
?>