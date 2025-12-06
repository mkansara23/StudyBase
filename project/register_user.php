<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
</head>
<body>
    <h1>Register New User</h1>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';

        $user_name = $_POST['user_name'];
        $major = $_POST['major'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($user_name) || empty($email) || empty($password)) {
            echo "<p style='color:red'>Error: Name, Email, and Password are required.</p>";
        } else {
            $sql = "INSERT INTO Users (user_name, major, email, password) 
                    VALUES ('$user_name', '$major', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                echo "<div style='border: 2px solid green; padding: 10px; margin: 10px 0; display: inline-block;'>";
                echo "<h3 style='color:green; margin-top:0;'>Registration Successful!</h3>";
                echo "<p>Your User ID is: <strong>" . $last_id . "</strong></p>";
                echo "<p>Please remember this ID to login.</p>";
                echo "</div>";
            } else {
                echo "<p style='color:red'>Error: " . $conn->error . "</p>";
            }
        }
        $conn->close();
    }
    ?>

    <form method="post" action="register_user.php">
        <label for="user_name">Name:</label>
        <input type="text" id="user_name" name="user_name" maxlength="30" required><br><br>

        <label for="major">Major:</label>
        <input type="text" id="major" name="major" maxlength="30"><br><br>

        <label for="email">Email (university email):</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>
    
    <br>
    <a href="index.html">Back to Login</a>
</body>
</html>