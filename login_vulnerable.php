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

$sql = "SELECT user_id, user_name, email, major FROM Users WHERE user_id = '$user_id' AND password = '$user_password'";

echo "<h3>SQL Query Executed:</h3>";
echo "<p>" . $sql . "</p>";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Login Successful! User Details:</h3>";
    while($row = $result->fetch_assoc()) {
        echo "User ID: " . $row["user_id"]. " - Name: " . $row["user_name"]. " - Major: " . $row["major"]. "<br>";
    }
} else {
    echo "0 results (Login Failed)";
}

$conn->close();
?>

