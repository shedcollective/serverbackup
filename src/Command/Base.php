<?php

namespace ServerBackup\Command;
class Base {

    /**
     * Describes what the command does
     */
    const INFO = '<error>Undefined</error>';

    // --------------------------------------------------------------------------

    /**
     * A reference to the aprent app instance
     *
     * @var \ServerBackup\App
     */
    protected $oApp;

    // --------------------------------------------------------------------------

    public function __construct($oApp)
    {
        $this->oApp = $oApp;
    }

    // --------------------------------------------------------------------------

    /**
     * Called when the command is executed
     */
    public function execute()
    {
    }
}
