## Introduction

We had a problem.. Ok not really a problem, but.. well.. nevermind. 

We had web servers that were hositng sites. and needed backups. We had backups, just not ones that made sense for how we worked. We wanted each user directory zipped with the date, and each database exported and zipped - also with the date.

Enter this little repo. 

The goal here is for the user to enter a couple of values in the ServerBackupSettings.php file and run the server_backup.php file via cron. 

Asssuming that you didn't make any mistakes in entering things, after some amount of time your files should be uploaded to the FTP of your choice.


## Installation

Download all the files in this repository to a folder on your server..

It is not recommended that this is placed in a location on your server where it can be accessed from a public URL. 

## Usage

This can be run by setting up a cron job. something like "php -q /path/to/server_backup.php" is pretty sufficient. currently, the script only allows for daily runs(the date paramter doens't have time in it... yet.)


## DISCLAIMER
**Use at your own risk. This script intentionally does not write to any of the directories, or attempt in any way to alter the databases that we are dumping, but odd things happen. Please be careful. Also: this script does absolutely, positively nothing to verify that the zip archives are usable. This should be part of your workflow for disaster recovery anyway, right?**

## Issues and suggestions
If you find issues with the way teh plugin works, or have a suggestion as to how you think it could be improved in later release or future development, please [submit an issue](https://github.com/mcyrulik/server_backup/issues)

## Versions
* **v1.0.0** - Initial release.
