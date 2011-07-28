<?php

	include "framework/bootstrap.php";
    Amandla::app() -> trigger("mojuba");
    Config::migrate(0, "dreamcord", "mysql", "localhost", "root", "");
?>
<br><br><br><br><br><br><br><br><br>
   <?php Amandla::printHistory("history.htm", 'w') ?>
s


