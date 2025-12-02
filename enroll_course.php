<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll in Course</title>
</head>
<body>
    <h1>Enroll User in Course</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';

        $user_id = $_POST['user_id'];
        $course_id = $_POST['course_id'];

        if (empty($user_id) || empty($course_id)) {
             echo "<p style='color:red'>Error: User ID and Course ID are required.</p>";
        } else {
            $sql = "INSERT INTO Enrolled_Courses (course_id, user_id) 
                    VALUES ('$course_id', '$user_id')";

            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green'>Enrolled in course successfully!</p>";
            } else {
                echo "<p style='color:red'>Error: " . $conn->error . "</p>";
            }
        }
        $conn->close();
    }
    ?>

    <form method="post" action="enroll_course.php">
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" maxlength="10" required><br><br>

        <label for="course_id">Course ID:</label>
        <input type="text" id="course_id" name="course_id" maxlength="10" required><br><br>

        <button type="submit">Enroll</button>
    </form>

    <br>
    <a href="index.html">Back to Home</a>
</body>
</html>
