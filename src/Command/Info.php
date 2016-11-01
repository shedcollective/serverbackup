<?php

namespace ServerBackup\Command;

use ServerBackup\App;
use ServerBackup\Helper\Output;

class Info extends Base {

    /**
     * Describes what the command does
     */
    const INFO = 'Displays this help message';

    // --------------------------------------------------------------------------

    /**
     * Called when the command is executed
     */
    public function execute()
    {
        Output::line('This is a simple, easily configurable, tool for backing up various important');
        Output::line('aspects of a web server.');
        Output::line();
        Output::line('Version:  ' . App::VERSION);
        Output::line();
        Output::line('Home:     https://github.com/shedcollective/serverbackup');
        Output::line('Issues:   https://github.com/shedcollective/serverbackup/issues');
        Output::line();
        Output::line('<comment>Available commands</comment>');

        $aClasses  = scandir(__DIR__ );
        $aCommands = [];

        foreach ($aClasses as $sClass) {

            $sClass = basename($sClass, '.php');
            $sClassName = 'ServerBackup\\Command\\' . $sClass;
            $sClass = strtolower($sClass);

            if ($sClass != 'base') {
                $aCommands[$sClass] = $sClassName::INFO;
            }
        }

        $iLength = max(array_map('strlen', array_keys($aCommands)));

        foreach ($aCommands as $sCommand => $sInfo) {
            Output::line('  <comment>' . str_pad($sCommand, $iLength) . '</comment> - ' . $sInfo);
        }
    }
}
