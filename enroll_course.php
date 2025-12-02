<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login_secure.html'>login</a> to enroll in a course.");
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$courses_result = $conn->query("SELECT course_id, course_code, course_name FROM Courses");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];

    if (empty($course_id)) {
         $message = "<p style='color:red'>Error: Please select a course.</p>";
    } else {
        $sql = "INSERT INTO Enrolled_Courses (course_id, user_id) 
                VALUES ('$course_id', '$user_id')";

        if ($conn->query($sql) === TRUE) {
            $message = "<p style='color:green'>Successfully enrolled in the course!</p>";
        } else {
            $message = "<p style='color:red'>Error: " . $conn->error . "</p>";
            if (strpos($conn->error, 'Duplicate entry') !== false) {
                 $message = "<p style='color:orange'>You are already enrolled in this course.</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll in Course</title>
</head>
<body>
    <h1>Enroll in Course</h1>
    <p>Student: <strong><?php echo $user_name; ?></strong> (ID: <?php echo $user_id; ?>)</p>

    <?php if (isset($message)) echo $message; ?>

    <form method="post" action="enroll_course.php">
        <label for="course_id">Select Course to Enroll:</label><br>
        <select id="course_id" name="course_id" required>
            <option value="">-- Select Course --</option>
            <?php
            if ($courses_result->num_rows > 0) {
                while($row = $courses_result->fetch_assoc()) {
                    echo "<option value='" . $row['course_id'] . "'>" . $row['course_code'] . " - " . $row['course_name'] . "</option>";
                }
            }
            ?>
        </select><br><br>

        <button type="submit">Enroll</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>