<?php

function getDbConnection() {
    try {
        
        $ip = "localhost";
        $port = 3306;
        $user = "root";
        $password = ""; // daabase password
        $database = "dentist";

        // SSL configuration
        $ssl_ca = ""; //path
        $ssl_cert = ""; //path
        $ssl_key = ""; //path

        
        foreach ([$ssl_ca, $ssl_cert, $ssl_key] as $file) {
            if (!file_exists($file)) {
                throw new Exception("File does not exist: $file");
            }
        }

        
        $conn = mysqli_init();

        if (!$conn) {
            throw new Exception("Failed to initialise the connection.");
        }

        // SSL setup
        mysqli_ssl_set($conn, $ssl_key, $ssl_cert, $ssl_ca, null, null);
        

        // Conect to database
        if (!mysqli_real_connect($conn, $ip, $user, $password, $database, $port, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
            throw new Exception("Connection error: " . mysqli_connect_error());
        }

        // echo "Connection established!";
        return $conn;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    return null;
}


$db = getDbConnection();

?>
