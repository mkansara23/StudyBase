<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Study Group</title>
</head>
<body>
    <h1>Create Study Group</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';

        $group_id = $_POST['group_id'];
        $description = $_POST['description'];
        $max_participants = $_POST['max_participants'];
        $host_id = $_POST['host_id'];
        $course_id = !empty($_POST['course_id']) ? $_POST['course_id'] : NULL; // Handle optional
        $location_id = $_POST['location_id'];
        $day = $_POST['day'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        
        // Checkbox handling: if checked, value is 1, else 0
        $repeat = isset($_POST['repeat']) ? 1 : 0;

        // Simple validation
        if (empty($group_id) || empty($host_id) || empty($location_id)) {
             echo "<p style='color:red'>Error: Group ID, Host ID, and Location ID are required.</p>";
        } else {
            // Insert Query
            // Note: For course_id, we need to handle NULL values properly in SQL string if it's empty
            $course_val = $course_id ? "'$course_id'" : "NULL";

            $sql = "INSERT INTO Study_Groups (group_id, description, max_participants, current_participants, host_id, course_id, location_id, start_time, end_time, day, repeat_flag) 
                    VALUES ('$group_id', '$description', $max_participants, 0, '$host_id', $course_val, '$location_id', '$start_time', '$end_time', '$day', $repeat)";

            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green'>Study Group created successfully!</p>";
            } else {
                echo "<p style='color:red'>Error: " . $conn->error . "</p>";
                // Common error: Host ID or Location ID doesn't exist
                if (strpos($conn->error, 'foreign key constraint') !== false) {
                    echo "<p>Tip: Ensure the Host ID (User) and Location ID exist in the database first.</p>";
                }
            }
        }
        $conn->close();
    }
    ?>

    <form method="post" action="create_study_group.php">
        <label for="group_id">Group ID:</label>
        <input type="text" id="group_id" name="group_id" maxlength="10" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" maxlength="1024" rows="4" cols="50"></textarea><br><br>

        <label for="max_participants">Max Participants:</label>
        <input type="number" id="max_participants" name="max_participants" min="1" max="100" value="10" required><br><br>

        <label for="host_id">Host User ID:</label>
        <input type="text" id="host_id" name="host_id" maxlength="10" required><br><br>

        <label for="course_id">Course ID (optional):</label>
        <input type="text" id="course_id" name="course_id" maxlength="10"><br><br>

        <label for="location_id">Location ID:</label>
        <input type="text" id="location_id" name="location_id" maxlength="10" required><br><br>

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
    <a href="index.html">Back to Home</a>
</body>
</html>
