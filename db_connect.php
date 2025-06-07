<?php
$servername = "localhost"; // Change if necessary
$username = "root"; // Default for XAMPP
$password = ""; // Default is empty
$database = "faculty_assessment";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
