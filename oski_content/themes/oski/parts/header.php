<section class='wrapper'>
<?php if (xcheck('mode', 'read', array($_GET, $_COOKIE))) include 'header_read.php'; else if (xcheck('mode', 'light', array($_GET, $_COOKIE))) include 'header_light.php'; else include 'header_normal.php' ?>
<section class='main' id='main'>