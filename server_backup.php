<?php

set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);

// include a config class.. everything we should need should be in there.
require_once "ServerBackupSettings.php";

// Include the class that is going to do each task.
require_once "ServerBackupWorker.php";

require_once "ftp.class.php";

// basically an instance of our settings..
$backup_settings = new ServerBackupSettings();

$date = date("Y-m-d");
// instantiate our worker...
$worker = new ServerBackupWorker();

// now get a user directory list. lets archive all of those.
$user_dir_list = $worker->getUserDirectoryList($backup_settings->getUserDirRoot());

// Now that we have it.. loop and do some stuff.
foreach ($user_dir_list as $directory) {

    $archive_name = "{$directory['dir']}-{$date}.zip";
    $archive_name = $backup_settings->getTempDocumentStoragePath().$archive_name;
    //echo $archive_name. "\n";

    // Initialize archive object
    echo "Archiving directory: {$directory['dir']} to {$archive_name}...";

    if (!empty($backup_settings->getHTMLDir())) {
        $dir_path = $directory['full_path'].$backup_settings->getHTMLDir()."/";
    } else {
        $dir_path = $directory['full_path'];
    }

    $worker->archiveFolderPath($archive_name, $dir_path, $backup_settings->getUserDirRoot());
    echo "Done.\n";
}

// So now let's do all the data base stuff.

// Make a PDO Connextion to DB server..
$server = $backup_settings->getDBHost();

$dbh = new PDO( "mysql:host=localhost", $backup_settings->getDBUser(), $backup_settings->getDBPass());
$sql = 'SHOW DATABASES;';

$db_path = $backup_settings->getTempDatabaseStoragePath();

foreach ($dbh->query($sql) as $row) {
    $database = $row['Database'];

    // A few databases that we dont want/need to back up,
    if ($database == 'information_schema' || $database == 'performance_schema') {
        continue;
    }

    $archive_name = "{$db_path}{$database}-{$date}.sql.zip";

    echo "Archiving Database: {$database} to {$archive_name}...";

    $worker->archiveDatabase($backup_settings->getDBUser(), $backup_settings->getDBPass(), $database, $archive_name);

    echo "Done.\n";
}

$ftp = new Ftp;
$ftp->connect($backup_settings->getFTPHost());
$ftp->login($backup_settings->getFTPUser(), $backup_settings->getFTPPass());

$ftp->mkDirRecursive($backup_settings->getRemoteDocumentStoragePath().$date."/");
$worker->uploadFiles($ftp, $backup_settings->getTempDocumentStoragePath(), $backup_settings->getRemoteDocumentStoragePath().$date."/");
$ftp->mkDirRecursive($backup_settings->getRemoteDatabaseStoragePath().$date."/");
$worker->uploadFiles($ftp, $backup_settings->getTempDatabaseStoragePath(), $backup_settings->getRemoteDatabaseStoragePath().$date."/");

$ftp->close();

$worker->cleanUpFiles($backup_settings->getTempDocumentStoragePath());
$worker->cleanUpFiles($backup_settings->getTempDatabaseStoragePath());

// ANd we are done..