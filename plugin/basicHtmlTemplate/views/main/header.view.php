<!DOCTYPE <?php $this -> render("main/doctypes/html5") ?>>
<html>
   <head>
       <meta http-equiv="generator" content="Codename Amandla">
       <title><?php echo Config::site_info("site_title"); ?> | <?php echo dataHelper::getCollection("themeData") && dataHelper::getCollection("themeData") -> title ? dataHelper::getCollection("themeData") -> title : ""; ?></title>
       <?php Amandla::trigger("headPlaceholder"); ?>
       <?php $this::getFonts(); ?>
       <?php $this::getStyles(); ?>
       <?php $this::getScripts(); ?>
       <?php
    /**
    * @todo Manage the stylesheets, javascripts, etcetera. I might need a helper for this :)
    */
       ?>
    </head>
    <body>
