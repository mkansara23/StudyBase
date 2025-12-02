<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login_secure.html'>login</a> to create a group.");
}

$host_id = $_SESSION['user_id']; 
$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : "";

$courses_result = $conn->query("SELECT course_id, course_code, course_name FROM Courses");
$locations_result = $conn->query("SELECT location_id, location_code, building, room FROM Location");

$sections_options = "";
if (!empty($selected_course_id)) {
    $sections_sql = "SELECT section_id, section_code 
                     FROM Sections 
                     WHERE section_of = '$selected_course_id'";
    $sections_result = $conn->query($sections_sql);
    
    if ($sections_result->num_rows > 0) {
        while($row = $sections_result->fetch_assoc()) {
            $sections_options .= "<option value='" . $row['section_id'] . "'>Section " . $row['section_code'] . "</option>";
        }
    } else {
        $sections_options = "<option value=''>No sections found</option>";
    }
} else {
    $sections_options = "<option value=''>-- Select a Course First --</option>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $max_participants = $_POST['max_participants'];
    $course_id = $_POST['final_course_id']; 
    $location_id = $_POST['location_id'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $repeat = isset($_POST['repeat']) ? 1 : 0;
    
    if (empty($course_id)) {
         $message = "<p style='color:red'>Error: Please select a course first.</p>";
    } else {
        $sql = "INSERT INTO Study_Groups (description, max_participants, current_participants, host_id, course_id, location_id, start_time, end_time, day, repeat_flag) 
                VALUES ('$description', $max_participants, 0, '$host_id', $course_id, '$location_id', '$start_time', '$end_time', '$day', $repeat)";

        if ($conn->query($sql) === TRUE) {
            $new_group_id = $conn->insert_id;
            $message = "<p style='color:green'>Study Group created successfully! Group ID: <strong>$new_group_id</strong></p>";
        } else {
            $message = "<p style='color:red'>Error: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Study Group</title>
    <script>
        function reloadWithCourse() {
            var courseId = document.getElementById("course_selector").value;
            window.location.href = "create_study_group.php?course_id=" + courseId;
        }
    </script>
</head>
<body>
    <h1>Create Study Group</h1>
    <p>Host: <strong><?php echo $_SESSION['user_name']; ?></strong> (ID: <?php echo $host_id; ?>)</p>

    <?php if (isset($message)) echo $message; ?>

    <label for="course_selector"><strong>Step 1: Select Course</strong></label><br>
    <select id="course_selector" onchange="reloadWithCourse()">
        <option value="">-- Select Course --</option>
        <?php
        if ($courses_result->num_rows > 0) {
            while($row = $courses_result->fetch_assoc()) {
                $selected = ($row['course_id'] == $selected_course_id) ? "selected" : "";
                echo "<option value='" . $row['course_id'] . "' $selected>" . $row['course_code'] . " - " . $row['course_name'] . "</option>";
            }
        }
        ?>
    </select>
    <br><br>

    <form method="post" action="create_study_group.php">
        <input type="hidden" name="final_course_id" value="<?php echo $selected_course_id; ?>">

        <label for="section_id"><strong>Step 2: Select Section</strong></label><br>
        <select id="section_id" name="section_id">
            <?php echo $sections_options; ?>
        </select><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" maxlength="1024" rows="4" cols="50"></textarea><br><br>

        <label for="max_participants">Max Participants:</label>
        <input type="number" id="max_participants" name="max_participants" min="1" max="100" value="10" required><br><br>

        <label for="location_id">Location:</label>
        <select id="location_id" name="location_id" required>
            <option value="">-- Select Location --</option>
            <?php
            if ($locations_result->num_rows > 0) {
                while($row = $locations_result->fetch_assoc()) {
                    echo "<option value='" . $row['location_id'] . "'>" . $row['location_code'] . " - " . $row['building'] . " " . $row['room'] . "</option>";
                }
            }
            ?>
        </select><br><br>

        <label for="day">Day of Week:</label>
        <select id="day" name="day" required>
            <option value="">--Select--</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select><br><br>

        <label for="start_time">Start Time (HH:MM):</label>
        <input type="time" id="start_time" name="start_time" required><br><br>

        <label for="end_time">End Time (HH:MM):</label>
        <input type="time" id="end_time" name="end_time" required><br><br>

        <label for="repeat">Repeats Weekly:</label>
        <input type="checkbox" id="repeat" name="repeat" value="1"><br><br>

        <button type="submit">Create Group</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>