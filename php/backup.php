<?php

$DB_USER = 'root';
$DB_PASSWORD = 'Ronaldo0709!'; // Password to MySQL
$DB_NAME = 'dentist';
$BACKUP_DIR = 'C:\Users\wikto\Desktop\backup'; // Path

$date = date('Y-m-d_H-i-s'); 

$backup_file = $BACKUP_DIR . '\\' . $DB_NAME . '_' . $date . '.sql';

$mysqldump_path = 'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe'; // Path to mysqldump.exe

$command = "\"$mysqldump_path\" -u $DB_USER -p$DB_PASSWORD $DB_NAME > \"$backup_file\"";

exec($command, $output, $return_var);

if ($return_var === 0) {
    echo "Database backup was completed successfully: $backup_file";
} else {
    echo "An error occurred while performing the backup.";
}
// If you want the backup to be automatic, you need to configure Task Scheduler
?>

