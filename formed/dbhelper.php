<?php
// Increase memory limit if necessary
ini_set('memory_limit', '1024M'); // Increase to 1 GB or as needed

// Database connection parameters
$servername = "localhost"; // Your database server name
$username = "root"; // Your database username
$password = ""; // Your database password
$database = "formed"; // Your database name

// Create connection
$db = new mysqli($servername, $username, $password, $database);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Optionally set the charset for the connection
$db->set_charset("utf8");

// You can also define a function to close the connection if needed
function closeConnection($db) {
    if ($db) {
        $db->close();
    }
}
?>