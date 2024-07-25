<?php
// Define database connection constants
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'pos_system_php');

// Establish a connection to the database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

echo "Connected successfully ";

// Close the database connection
//mysqli_close($conn);
?>
