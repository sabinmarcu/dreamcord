<?php
class helloWorldModule extends modulePrototype{
    protected $_viewDir = "views/";
    public static function obj($c = __CLASS__)   {
        return parent::obj($c);
    }
    public function helloAction()  {
        self::render("default", array( "ip" => $_SERVER['REMOTE_ADDR']));
    }
    public function hellocliAction()  {
        echo "Hello Terminal!";
    }
}