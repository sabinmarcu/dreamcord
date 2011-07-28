<?php
class fileHelper extends Singleton{

    public static function ensureFile($path)    {
        self::ensureFolder($path);
        if (!file_exists($path)) {     $file = fopen($path, 'w');fclose($file);  chmod($path, 0775);   }
        return $path;
    }
    public static function ensureFolder($path)  {
        $path = explode("/", $path); $f = count($path) - 1; 
        $dir = $path[0];
        for($i = 1; $i < $f; $i++)  {
             $dir = $dir . "/" . $path[$i];
             if (!file_exists($dir))   {
                 mkdir($dir);     
             chmod($dir, 0775);   
             }
        }        
    }
    public static function trashDir($path)  {
        if (!file_exists(($path))) return false;
        if (substr($path, 0, strlen($path) - 2) !== "/") $path .= "/";
        $dir = opendir($path)  ;
        while($file = readdir($dir))    {
            if (is_dir($path.$file) && strpos($file, ".") !== 0)  self::trashDir($path.$file);
            else if (!is_dir($path.$file)) unlink($path.$file);
        }
        rmdir($path);
    }
    public static function pluginDir($dir) {
        return Config::directories("_root_").Config::directories("_plugins_").Config::directories($dir);
    }
    public static function inc($file)   {
        if (is_array($file))   {
            if ($file['type'] == "css") foreach($file['files'] as $f) return  self::inc($f);
            if ($file['type'] == "js") foreach($file['files'] as $f) return self::inc($f);
            if ($file['type'] == "fonts") return self::linkFonts($file['files']);
        } else {
            $name = stringHelper::trimext(end(explode("/", $file)));
            $ext = stringHelper::ext($file);
            if (strpos($name, ".") === 0) return NULL;
            if ($ext == "css")  return self::linkCSS($file, $name, $ext);
            if ($ext == "js") return self::linkJS($file, $name, $ext);
        }
    }
    private static  function linkCSS(&$path, &$name = NULL, &$ext = NULL)    {
        if (!$name) $name = stringHelper::trimext(end(explode("/", $file)));
        if (!$ext)  $ext = stringHelper::ext($file);
        return "<link rel='stylesheet' type='text/css' href='/" . str_replace(APPDIR, "", $path) . "' media='" . (in_array($name, array("screen", "print", "all")) ? $name : "") ."' />\n";
    }
    private static function linkJS(&$path, &$name = NULL, &$ext = NULL) {
        if (!$name) $name = stringHelper::trimext(end(explode("/", $file)));
        if (!$ext)  $ext = stringHelper::ext($file);
        return "<script type='text/javascript' src='/" . str_replace(APPDIR, "", $path) . "'></script>\n";
}
    private static function linkFonts(&$fonts) {
        $r = "<style type='text/css'>";
        foreach($fonts as $font => $args)   :
            $exts =& $args["exts"]; $path =& $args["path"];
            $r .= "\n@font-face{";
            $r .= "\n\tfont-family: '".$font."';";
            $r .= "\n\tsrc :"; $t = "";
            $o = 0;
            foreach($exts as $ext)  {
                $t .= "\t\turl('" . $path . "." . $ext . "') format('";
                switch($ext)    {
                    case "ttf" : $t .= "truetype"; break;
                    case "otf" : $t .= "opentype"; break;
                    case "eot" : $t .= "embedded-opentype"; $o = 1; break;
                    default : $t .= $ext; break;
                }
                $t .= "'),\n";
            }
            if ($o) $r .= " url('" . $path . ".eot');\n\tsrc : local('☺'),\n";
            $r .= $t;
            $r = substr($r, 0, strlen($r) - 2) . ";\n}";
        endforeach;
        $r .= "\n</style>\n";
        return $r;
    }
}
?>
