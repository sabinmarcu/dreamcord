<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of envHandlerOverrideJuju
 *
 * @author marcusabin
 */
class envHandlerOverrideJuju extends environmentHandlerJuju {
    public function splitAction()    {
        parent::splitAction();
        self::logEvent("And overrided it");
    }
}

?>
