<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of envHandlerOverrideModule
 *
 * @author marcusabin
 */
class envHandlerOverrideModule extends environmentHandlerModule {
    public function splitAction()    {
        parent::splitAction();
        self::logEvent("And overrided it");
    }
}

?>
