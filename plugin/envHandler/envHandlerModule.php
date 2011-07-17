<?php
class envHandlerModule extends Singleton{
    public function  splitAction()  {
        if (isset($_SERVER['PWD'])) Amandla::trigger("cliRequestBase");
        else Amandla::trigger("urlRequestBase");
    }
}
