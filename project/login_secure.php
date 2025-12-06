<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studybase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];
$user_password = $_POST['password'];

$stmt = $conn->prepare("SELECT user_id, user_name FROM Users WHERE user_id = ? AND password = ?");
$stmt->bind_param("ss", $user_id, $user_password);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Login Status</h3>";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['user_name'] = $row['user_name'];

    echo "<h3 style='color:green'>Login Successful!</h3>";
    echo "<p>Welcome, " . $row['user_name'] . " (ID: " . $row['user_id'] . ")</p>";
    echo "<a href='dashboard.php'><strong>>> Continue to Dashboard</strong></a>";
} else {
    echo "<h3 style='color:red'>Login Failed</h3>";
    echo "<p>Invalid ID or Password.</p>";
    echo "<p><a href='login_secure.html'>Try Again</a></p>";
}

$stmt->close();
$conn->close();
?>