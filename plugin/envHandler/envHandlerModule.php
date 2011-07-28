<?php
class envHandlerModule extends Singleton{
    public static function obj($c = __CLASS__){
        return parent::obj($c);
    }
    public function  splitAction()  {
        if (isset($_SERVER['PWD'])) Amandla::trigger("cliRequestBase");
        else Amandla::trigger("urlRequestBase");
    }
}
