<!DOCTYPE <?php $this -> render("main/doctypes/html5") ?>>
<html>
   <head>
       <meta http-equiv="generator" content="Codename Amandla">
       <?php Amandla::trigger("headPlaceholder"); ?>
       <?php var_dump(self::$_name); ?>
       <?php self::getFonts(); ?>
       <?php self::obj() -> getStyles(); ?>
       <?php self::obj() -> getScripts(); ?>
       <?php
    /**
    * @todo Manage the stylesheets, javascripts, etcetera. I might need a helper for this :)
    */
       ?>
    </head>
    <body>
