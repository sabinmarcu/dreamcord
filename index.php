<?php

	include "framework/bootstrap.php";
    Amandla::app() -> trigger("mojuba");
echo Config::site_info("site_title");
?>




