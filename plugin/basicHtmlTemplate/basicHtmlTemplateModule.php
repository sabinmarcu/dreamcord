<?php
class basicHtmlTemplateModule extends modulePrototype   {
    protected $_viewDir = "views/";
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