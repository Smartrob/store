<?php
$host = "localhost";
$db_name = "store_management";
$username = "root"; // or any DB user
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
