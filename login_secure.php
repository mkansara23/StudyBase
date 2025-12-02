<?php
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

$stmt = $conn->prepare("SELECT user_id, user_name, email, major FROM Users WHERE user_id = ? AND password = ?");

$stmt->bind_param("ss", $user_id, $user_password);

$stmt->execute();

$result = $stmt->get_result();

echo "<h3>Prepared Statement Executed</h3>";
echo "<p>Template: SELECT user_id, user_name, email, major FROM Users WHERE user_id = ? AND password = ?</p>";

if ($result->num_rows > 0) {
    echo "<h3>Login Successful! User Details:</h3>";
    while($row = $result->fetch_assoc()) {
        echo "User ID: " . $row["user_id"]. " - Name: " . $row["user_name"]. " - Major: " . $row["major"]. "<br>";
    }
} else {
    echo "0 results (Login Failed)";
}

$stmt->close();
$conn->close();
?>

