# S3 Backup Script for Servers

Backs up databases matching a given Regular Expression, and/or pre-defined directories to S3.

---

	This is an internal tool for Shed Collective.
	No warranties are given or implied and you use at your own risk!

---


## Installation
@todo - distribute via apt-get, yum, linux-brew etc if possible


## Configuration

Place a file at `~/.serverbackuprc` with the configuration array you wish to use, as a JSON object.

Defaults:

```json
{
    'database': {
        'enabled': false
        'pattern': '^(information_schema|mysql|performance_schema|test)',
        'host': '',
        'user': '',
        'pass': ''
    },
    'filesystem': {
        'enabled': false,
        'targets': []
    },
    's3': {
        'bucket': '',
        'prefix': '',
        'suffix': '',
        'access_key': '',
        'access_secret': ''
    },
    'hostname': '',
    'temp_dir': '/tmp/serverbackup',
    'retainment': 5
}
```


### The MySQL User

It is recommended that backups are performed by a user with the minimal required permissions. The following steps will create a MySQL user named `backup` with password `my-password` which has the minimum amount of permissions required.

1. `CREATE USER 'backup'@'localhost' IDENTIFIED BY 'my-password';`
2. `GRANT SELECT, LOCK TABLES ON mysql.* TO 'backup'@'localhost';`
3. `GRANT SELECT, LOCK TABLES, SHOW VIEW, EVENT, TRIGGER ON *.* TO 'backup'@'localhost';`


## Backing up

Set up Cron to execute the backup as often as you'd like. Something like `03 03 * * * serverbackup backup` should do the trick; or, of course, run it manually whenever you want.


## Options

The following commands are available to you:

- `backup` - Performs a complete backup using the options found at `~/.serverbackuprc`
- `test`   - Tests backup integrity for the latest backups found at the target defined by `~/.serverbackuprc`


## Compiling

This project uses [box](https://github.com/box-project/box2) to build the PHAR.

```bash
$ ./build.sh
```

A new file called `dist/serverbackup.phar` will be available.

---

#### Roadmap

- [x] ~~Other database patterns/make chosen databases configurable.~~ (v1.1.0)
- [x] ~~Specify username and password.~~ (v2.1.0)
- [ ] Implement a more secure way of storing the MySQL credentials.
- [ ] Better logging.
  - [ ] Show the size of the folder we’re about to archive
  - [ ] Time stamp each line
- [ ] Exclude certain paths (e.g caches and logs)
- [ ] Backup directories and databases individually and push/purge as we go (basically reduce the amount of data on the server at any one time)
- [ ] Test integrity of backups
- [ ] Restore backups
