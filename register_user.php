<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
</head>
<body>
    <h1>Register New User</h1>
    
    <?php
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';

        $user_id = $_POST['user_id'];
        $user_name = $_POST['user_name'];
        $major = $_POST['major'];
        $email = $_POST['email'];

        // Simple validation
        if (empty($user_id) || empty($user_name) || empty($email)) {
            echo "<p style='color:red'>Error: User ID, Name, and Email are required.</p>";
        } else {
            // Simple Insert Query
            $sql = "INSERT INTO Users (user_id, user_name, major, email) 
                    VALUES ('$user_id', '$user_name', '$major', '$email')";

            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green'>New user registered successfully!</p>";
            } else {
                echo "<p style='color:red'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }
        $conn->close();
    }
    ?>

    <form method="post" action="register_user.php">
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" maxlength="10" required><br><br>

        <label for="user_name">Name:</label>
        <input type="text" id="user_name" name="user_name" maxlength="30" required><br><br>

        <label for="major">Major:</label>
        <input type="text" id="major" name="major" maxlength="30"><br><br>

        <label for="email">Email (university email):</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Register</button>
    </form>
    
    <br>
    <a href="index.html">Back to Home</a>
</body>
</html>
