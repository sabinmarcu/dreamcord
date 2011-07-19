<?php
/**
 * @todo stylesheet getter, javascript getter, font getter, etc.
 */
class basicHtmlTemplateModule extends modulePrototype   {
    protected $_viewDir = "views/";

    public function __construct()   {
        parent::__construct();
        Config::load(Config::directories("_root_") . Config::directories("_plugins_") . Config::directories($this -> _name) . "configs/config");
    }

    public function renderHtmlPageAction()  {
        $this -> renderHeadSectionEvent();
        Amandla::trigger("bodyPlaceholder");
        $this -> renderBodySectionEvent();
    }
    private function renderHeadSectionEvent() {
        $this -> render("main/header");




    }
    private function renderBodySectionEvent() {
        $this -> render("main/footer");

    }
}