<?php

namespace ServerBackup\Command;

use ServerBackup\Helper\Output;

class Backup extends Base {

    /**
     * Describes what the command does
     */
    const INFO = 'Performs a backup';

    // --------------------------------------------------------------------------

    /**
     * Called when the command is executed
     */
    public function execute()
    {
        Output::line('<error>@todo - Backups are on the road map and will be completed soon</error>');
    }
}
