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
        if (end($path) !== "/") $path .= "/";
        $dir = readdir($path)  ;
        while($file = readdir($dir))    {
            if (is_dir($path.$file))  self::trashDir($path.$file);
            else unlink($path.$file);
        }
        unlink($path);
    }
}
?>
