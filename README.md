# S3 Backup Script for Servers

Backs up databases ending in `%_prod` and `%_stage` to S3.

---

**This is an internal tool for Shed Collective. No warranties are given or implied and you use at your own risk!**

---

### Configuration

You will need to specify a file at the same level as `backup.sh` called `config.sh`; looks like this, adjust to your needs:

	# Relative path; relative to /; no trailing slash
	BACKUPPATH="root/my-server-backups"
	
	# Name of the S3 Bucket to save to
	S3BUCKET="my-server-backups"

### Dependancies

Additionally, `s3cmd` will need to be installed. To install:

**1. For OSX (using homebrew)**

1. `brew install s3cmd`

**1. For CentOS (using yum)**

1. `cd /etc/yum.repos.d`
2. `wget http://s3tools.org/repo/RHEL_6/s3tools.repo`
3. `yum install s3cmd`

**2. Configure**

1. `s3cmd --configure` and follow the on-screen instructions.

### Backing up

Set up Cron to execute the backup as often as you'd like. Something like `03 11,16 * * * /root/shedbackups/backup.sh` should do the trick.


---

#### Roadmap

1. Other database patterns/make chosen databases configurable.