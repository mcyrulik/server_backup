<?php

class ServerBackupSettings {
    private $db_user = "<db_user>";
    private $db_pass = "<db_pass>";
    private $db_host = "<db_host>";

    private $user_dir_root = "<path to your web files>";

    // this is an add on that can be appended to the calculated path so when we go to zip the directory, we get only the 
    // appropriate files and not some extra stuff that's not web related....
    private $html_dir = "";

    // This needs to be somewhere writeable..
    private $temp_local_storage_documents = "<Where to store the documents temporarily>";
    private $temp_local_storage_databases = "<Where to store the databases temporarily>";

    private $remote_documents_dir = "<remote documents directory>";
    private $remote_database_dir = "<remote databases directory>";

    private $ftp_host = '<ftp_host>';
    private $ftp_user = '<ftp_user>';
    private $ftp_pass = '<ftp_pass>';

    public function __construct () {
        // We should check that the user dir root exists..
        if (!file_exists($this->user_dir_root)) {
            throw new Exception("User directory root does not exist.");
        }

        if (!is_dir($this->user_dir_root)) {
            throw new Exception("User directory root must be a directory.");
        }

        // We should check that the temp storage exists and is writeable.. 
        if (!file_exists($this->temp_local_storage_documents)) {
            throw new Exception("Temp Document Storage does not exist.");
        }

        if (!is_dir($this->temp_local_storage_documents)) {
            throw new Exception("Temp Document Storage must be a directory.");
        }

        if (!is_writable($this->temp_local_storage_documents)) {
            throw new Exception("Temp Document Storage must be writeable by PHP.");
        }

        // We should check that the temp storage exists and is writeable.. 
        if (!file_exists($this->temp_local_storage_databases)) {
            throw new Exception("Temp Database Storage does not exist.");
        }

        if (!is_dir($this->temp_local_storage_databases)) {
            throw new Exception("Temp Database Storage must be a directory.");
        }

        if (!is_writable($this->temp_local_storage_databases)) {
            throw new Exception("Temp Database Storage must be writeable by PHP.");
        }
    }

    public function getDBUser() {
        return $this->db_user;
    }

    public function getDBPass() {
        return $this->db_pass;
    }

    public function getDBHost() {
        return $this->db_host;
    }

    public function getFTPUser() {
        return $this->ftp_user;
    }

    public function getFTPPass() {
        return $this->ftp_pass;
    }

    public function getFTPHost() {
        return $this->ftp_host;
    }

    public function getUserDirRoot() {
        return $this->user_dir_root;
    }

    public function getTempDocumentStoragePath() {
        return $this->temp_local_storage_documents;
    }

    public function getTempDatabaseStoragePath() {
        return $this->temp_local_storage_databases;
    }

    public function getHTMLDir() {
        return $this->html_dir;
    }

    public function getRemoteDocumentStoragePath() {
        return $this->remote_documents_dir;
    }

    public function getRemoteDatabaseStoragePath() {
        return $this->remote_database_dir;
    }

}