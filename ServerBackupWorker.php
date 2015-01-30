<?php

class ServerBackupWorker {
    
    public function __construct () {
        
    }

    public function getUserDirectoryList($user_dir_root = null) {
        // make sure that the user dir root is not empty.
        if ($user_dir_root == null || empty($user_dir_root)) {
            throw new Exception("User directory root cannot be null.");
        }

        $scanned_directory = array_diff(scandir($user_dir_root), array('..', '.'));

        $return_array = array();

        foreach ($scanned_directory as $dir) {
            if (is_dir($user_dir_root."/".$dir)) {
                $temp = array(
                    'dir' => $dir,
                    'full_path' => $user_dir_root . $dir . "/"
                    );
                array_push($return_array, $temp);
            } 
        }

        return $return_array;
    }

    public function archiveFolderPath($archive_name, $source_path, $remove_path) {
        $shell_command = "cd {$source_path}; zip -r {$archive_name} ./";
        @shell_exec($shell_command);
    }

    public function archiveDatabase($username, $password, $database, $archivename) {
        $shell_command = "MYSQL_PWD={$password} mysqldump {$database} -u'{$username}' | gzip -c | cat > {$archivename}";

        @shell_exec($shell_command);
    }

    public function uploadFiles($ftp, $file_path, $remote_path) {
        $scanned_directory = array_diff(scandir($file_path), array('..', '.')); 

        foreach ($scanned_directory as $file) {
            echo "Local Path: ".$file_path.$file." - Remote Path: ".$remote_path.$file."\n";
            $ftp->put($remote_path.$file, $file_path.$file, FTP_BINARY);
            
        }
        //echo $file_path;
    }

    public function cleanUpFiles($file_path) {
        $scanned_directory = array_diff(scandir($file_path), array('..', '.')); 

        foreach ($scanned_directory as $file) {
            echo "Unlinking: ".$file_path.$file."\n";
            unlink($file_path.$file);
            
        }
        //echo $file_path;
    }
}