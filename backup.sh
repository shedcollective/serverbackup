#!/bin/sh

# Include the config file
CURDIR="$(dirname "$0")"
source "$CURDIR/config.sh";

# Date, used for file naming
DATE=`date "+%Y%m%d%H%M"`

# Server Hostname
HOSTNAME=`hostname`

# --------------------------------------------------------------------------

echo "Starting database backups on $HOSTNAME";

# MYSQL Dump
for DB in $(mysql -e "SHOW DATABASES WHERE \`Database\` REGEXP '$DBREGEX'" -s --skip-column-names); do

    echo "Backing up '$DB'";

    # Make Backup folder
    mkdir -p "/$BACKUPPATH/backups/$DB/";

    # Dumpy dump
    mysqldump $DB > "/$BACKUPPATH/backups/$DB/$DATE.sql";

    # Zip it up
    tar -zcf "/$BACKUPPATH/backups/$DB/$DATE.sql.tar.gz" -C / "$BACKUPPATH/backups/$DB/$DATE.sql";
    rm -f "/$BACKUPPATH/backups/$DB/$DATE.sql";

done

# --------------------------------------------------------------------------

# Send backups offsite
echo "Syncing to S3"
s3cmd sync --skip-existing "/$BACKUPPATH/backups/" "s3://$S3BUCKET/$HOSTNAME/"

# --------------------------------------------------------------------------

# Delete backup files
echo "Deleting local backups";
rm -rf "/$BACKUPPATH/backups/";

# --------------------------------------------------------------------------

# CREATE LAST UPDATE FLAG
echo "Last updated: $DATE" > "/$BACKUPPATH/lastrun.md"