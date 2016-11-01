<?php

namespace ServerBackup\Command;

use ServerBackup\Helper\Output;

class Restore extends Base {

    /**
     * Describes what the command does
     */
    const INFO = 'Performs a restore';

    // --------------------------------------------------------------------------

    /**
     * Called when the command is executed
     */
    public function execute()
    {
        Output::line('<error>@todo - Backup restorations are on the road map and will be completed soon</error>');
    }
}
