<?php
class helloWorldModule extends modulePrototype{
    protected $_viewDir = "views/";
    public function helloAction()  {
        self::render("default", array( "ip" => $_SERVER['REMOTE_ADDR']));
    }
    public function hellocliAction()  {
        echo "Hello Terminal!";
    }
}