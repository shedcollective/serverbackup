<?php

namespace ServerBackup\Command;

use ServerBackup\Helper\Database;
use ServerBackup\Helper\Debug;
use ServerBackup\Helper\Output;
use ServerBackup\Helper\Timer;

class Backup extends Base
{

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
        $sCmd           = $this->oApp->command(1) ?: 'all';
        $aValidDbCmds   = ['database', 'db'];
        $aValidFileCmds = ['dir', 'directory', 'directories', 'file', 'files', 'filesystem'];
        if ($sCmd !== 'all' && !in_array($sCmd, $aValidDbCmds) && !in_array($sCmd, $aValidFileCmds)) {
            $aValidCmds = array_merge($aValidDbCmds, $aValidFileCmds);
            throw new \Exception(
                'Not a backup valid command, must be one of: ' . implode(', ', $aValidCmds)
            );
        }

        $bEnableDb   = $sCmd == 'all' || in_array($sCmd, $aValidDbCmds);
        $bEnableFile = $sCmd == 'all' || in_array($sCmd, $aValidFileCmds);

        if ($bEnableDb) {
            $this->backupDatabase();
        }

        if ($bEnableDb && $bEnableFile) {
            Output::line();
            Output::line('---');
            Output::line();
        }

        if ($bEnableFile) {
            $this->backupFilesystem();
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Performs the database backups
     */
    private function backupDatabase()
    {
        $sHostname = $this->getHostname();
        Output::line('Starting database backup on <comment>' . $sHostname . '</comment>');
        Output::line();

        // --------------------------------------------------------------------------

        //  Attempt DB connection
        $oDb = new Database(
            $this->oApp->config('database')->host,
            $this->oApp->config('database')->user,
            $this->oApp->config('database')->pass
        );

        // --------------------------------------------------------------------------

        Output::line('The following databases will be backed up:');

        $sSql     = 'SHOW DATABASES';
        $sInclude = $this->oApp->config('database')->pattern->include;
        $sExclude = $this->oApp->config('database')->pattern->exclude;

        if (!empty($sInclude) || !empty($sExclude)) {
            $sSql .= ' WHERE ';
        }

        if (!empty($sInclude)) {
            $sSql .= '`Database` REGEXP "' . $sInclude . '"';
        }

        if (!empty($sInclude) || !empty($sExclude)) {
            $sSql .= ' AND ';
        }

        if (!empty($sExclude)) {
            $sSql .= '`Database` NOT REGEXP "' . $sExclude . '"';
        }

        $oStatement = $oDb->query($sSql);
        $aDatabases = [];

        while ($oDatabase = $oStatement->fetchObject()) {
            $aDatabases[] = $oDatabase->Database;
            Output::line(' - <comment>' . $oDatabase->Database . '</comment>');
        }

        // --------------------------------------------------------------------------

        if (!$this->oApp->hasArg('dry-run')) {

            //  Start Timer
            $oTimer = new Timer();
            $oTimer->start();

            Output::line();
            Output::line('Beginning database backups...');
            Output::line();

            foreach ($aDatabases as $sDatabase) {

                Output::line('---');
                //  Prepare variables
                $sDumpFile    = '@todo';
                $sArchiveFile = '@todo';
                $sTarget      = '@todo';

                //  @todo - dump database
                Output::line('Dumping <comment>' . $sDatabase . '</comment> to <comment>' . $sDumpFile . '</comment>');

                //  @todo - archive dump
                Output::line('Archiving <comment>' . $sDumpFile . '</comment> to <comment>' . $sArchiveFile . '</comment>');

                //  @todo - push to target
                Output::line('Sending <comment>' . $sArchiveFile . '</comment> to <comment>' . $sTarget . '</comment>');

                //  @todo - delete local archive
                Output::line('Deleting <comment>' . $sArchiveFile . '</comment>');

                //  @todo - purge according to retainment rules
                Output::line('Purging old backups of <comment>' . $sDatabase . '</comment>');
            }

            Output::line();
            Output::line('Database backups complete.');

            // --------------------------------------------------------------------------

            $oTimer->stop();
            Output::line('Backup took ' . number_format($oTimer->duration(), 5) . ' seconds');
            Output::line();
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Performs the filesystem backups
     */
    private function backupFilesystem()
    {
        $sHostname = $this->getHostname();
        Output::line('Starting filesystem backup on <comment>' . $sHostname . '</comment>');
        Output::line();

        // --------------------------------------------------------------------------

        Output::line('The following directories will be backed up:');
        $aDirectories = (array) $this->oApp->config('filesystem')->targets;
        foreach ($aDirectories as $sDirectory) {
            Output::line(' - <comment>' . $sDirectory . '</comment>');
        }
        //  @todo - List directories

        if (!$this->oApp->hasArg('dry-run')) {

            //  Start Timer
            $oTimer = new Timer();
            $oTimer->start();

            Output::line();
            Output::line('Beginning filesystem backups...');
            Output::line();

            foreach ($aDirectories as $sDirectory) {

                Output::line('---');
                //  Prepare variables
                $sArchiveFile = '@todo';
                $sTarget      = '@todo';

                //  @todo - archive directory
                Output::line('Archiving <comment>' . $sDirectory . '</comment> to <comment>' . $sArchiveFile . '</comment>');

                //  @todo - push to target
                Output::line('Sending <comment>' . $sArchiveFile . '</comment> to <comment>' . $sTarget . '</comment>');

                //  @todo - delete local archive
                Output::line('Deleting <comment>' . $sArchiveFile . '</comment>');

                //  @todo - purge according to retainment rules
                Output::line('Purging old backups of <comment>' . $sDirectory . '</comment>');
            }

            // --------------------------------------------------------------------------

            $oTimer->stop();
            Output::line();
            Output::line('Backup took ' . number_format($oTimer->duration(), 5) . ' seconds');
        }
    }

    // --------------------------------------------------------------------------

    private function getHostname()
    {
        return $this->oApp->config('hostname') ?: gethostname();
    }
}
