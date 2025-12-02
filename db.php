<?php
// Simple database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default for XAMPP/MAMP usually empty or 'root'
$dbname = "studybase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
