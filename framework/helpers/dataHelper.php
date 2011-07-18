<?php
class dataHelper extends Singleton{

    public static $_reccords = array();
    public static $_index = 0;

    public static function obj()   {
        return parent::obj(__CLASS__);
    }

    public static function &startRecording()    {
        self::$_reccords[self::$_index] = ""; self::$_index++;
        $ret = &self::$_reccords[self::$_index - 1];
        ob_start();
        return $ret;
    }
    public static function stopReccording()    {
        self::$_reccords[self::$_index - 1] = ob_get_contents();
        ob_end_clean();
        return self::$_reccords[self::$_index - 1];
    }
    public static function getReccording($index = NULL)   {
        if (isset($index)) :
            $ret = self::$_reccords[$index];
            unset(self::$_reccords[$index]);
            self::$_index--;
            return $ret;
        endif;
        $ret = self::$_reccords[self::$_index - 1];
        unset(self::$_reccords[self::$_index - 1]);
        self::$_index--;
        return $ret;
    }
    public static function flush(&$index)    {
        for($i = 0; $i <= self::$_index - 1; $i++) {
            if (self::$_reccords[$i] == $index)  {
                unset(self::$_reccords[$i]);
                self::$_index--; $i--;
            }
        }
    }
    public static function clean()  {
        self::$_reccords = array();
        self::$_index = 0;
    }
    public static function &store($variable) {
        self::$_reccords[self::$_index] = $variable; self::$_index++;
        $ret = &self::$_reccords[self::$_index - 1];
        return $ret;
    }
    public static function alter(&$var, $new)   {
        for($i = 0; $i <= self::$_index - 1; $i++) {
            if (self::$_reccords[$i] == $var)  {
                self::$_reccords[$i] = $new;
            }
        }
    }
    public static function remove($variable) {
        self::flush($variable);
    }

}