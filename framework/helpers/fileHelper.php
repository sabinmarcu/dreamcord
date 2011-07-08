<?php
class fileHelper extends Singleton{
    public static function ensureFile($path)    {
        $path = explode("/", $path); $f = count($path) - 1; 
                 $dir = $path[0];
        for($i = 1; $i < $f; $i++)  {
             $dir = $dir . "/" . $path[$i];
             if (!file_exists($dir))   {
                 mkdir($dir);   
                 chmod($dir, 0775);     
             }
        }
        $dir .= "/" . $path[$f];
        $file = fopen($dir, 'w');fclose($file);
        chmod($dir, 0775);   
        return $dir;
    }
    public static function trashDir($path)  {
        if (substr($path, 0, strlen($path) - 2) !== "/") $path .= "/";
        $dir = opendir($path)  ;
        while($file = readdir($dir))    {
            if (is_dir($path.$file) && strpos($file, ".") !== 0)  self::trashDir($path.$file);
            else if (!is_dir($path.$file)) unlink($path.$file);
        }
        rmdir($path);
    }
}
?>
