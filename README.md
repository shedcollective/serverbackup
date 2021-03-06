# S3 Backup Script for Servers

Backs up databases matching a given Regular Expression, and/or pre-defined directories to S3.

---

	This is an internal tool for Shed Collective.
	No warranties are given or implied and you use at your own risk!

---



## Configuration

You will need to specify a file at the same level as `backup.sh` called `config.sh`; looks like this, adjust to your needs:


    # Turn on DB Backup
    BACKUPDB=true

    # Turn on Directory Backup
    BACKUPDIR=true

	# Where to temporarily store backups locally
    # Relative path; relative to /; no trailing slash
    BACKUPPATH="root/my-server-backups"

    # Name of the S3 Bucket to upload to
    S3BUCKET="my-server-backups"

    # REGEX for database names to backup
    DBREGEX=".*_prod$|.*_stage$"

    # DB connection details
    DBUSER="backup"
    DBPASS="my-password"

    # Backup the following directories
    # Declare as an array; Relative path; relative to /; no trailing slash
    BACKUPDIRS[0]="root/my-website-dir"
    BACKUPDIRS[1]="root/another-website-dir"



## Dependancies

Additionally, `s3cmd` will need to be installed. To install:

**1. For OSX (using homebrew)**

1. `brew install s3cmd`

**1. For CentOS (using yum)**

1. `cd /etc/yum.repos.d`
2. `wget http://s3tools.org/repo/RHEL_6/s3tools.repo`
3. `yum install s3cmd`

**1. For Debian (using apt-get)**

1. `wget -O- -q http://s3tools.org/repo/deb-all/stable/s3tools.key | sudo apt-key add -`
2. `sudo wget -O /etc/apt/sources.list.d/s3tools.list http://s3tools.org/repo/deb-all/stable/s3tools.list`
3. `sudo apt-get update && sudo apt-get install s3cmd`

**2. Configure**

1. `s3cmd --configure` and follow the on-screen instructions.



## Database Backups

If required, turn database backups on by setting `BACKUPDB` to `true` in your config.

### The MySQL User

The following steps will create a MySQL user named `backup` with password `my-password` which has the minimum amount of permissions required.

1. `CREATE USER 'backup'@'localhost' IDENTIFIED BY 'my-password';`
2. `GRANT SELECT, LOCK TABLES ON mysql.* TO 'backup'@'localhost';`
3. `GRANT SELECT, LOCK TABLES, SHOW VIEW, EVENT, TRIGGER ON *.* TO 'backup'@'localhost';`

## Directory Backups

If required, turn directory backups on by setting `BACKUPDIR` to `true` in your config.


## Backing up

Set up Cron to execute the backup as often as you'd like. Something like `03 03 * * * /backups/serverbackup` should do the trick.


---

#### Roadmap

1. ~~Other database patterns/make chosen databases configurable.~~ (v1.1.0)
2. ~~Specify username and password.~~ (v2.1.0)
3. Implement a more secure way of storing the MySQL credentials.
4. Better logging.
  1. Show the size of the folder we’re about to archive
  2. Time stamp each line
5. Exclude certain paths (e.g caches and logs)
6. Backup directories and databases individually and push/purge as we go (basically reduce the amount of data on the server at any one time)
