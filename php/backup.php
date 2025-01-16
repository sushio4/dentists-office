<?php
$DB_USER = 'root';
$DB_PASSWORD = ''; // Password to MySQL
$DB_NAME = 'dentist';
$BACKUP_DIR = ''; // Path

$date = date('Y-m-d_H-i-s'); 

$backup_file = $BACKUP_DIR . '\\' . $DB_NAME . '_' . $date . '.sql';

$mysqldump_path = ''; // Path to mysqldump.exe

$command = "\"$mysqldump_path\" -u $DB_USER -p$DB_PASSWORD $DB_NAME > \"$backup_file\"";

exec($command, $output, $return_var);

if ($return_var === 0) {
    echo "Database backup was completed successfully: $backup_file";
} else {
    echo "An error occurred while performing the backup.";
}
?>
