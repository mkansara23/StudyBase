<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Study Groups</title>
</head>
<body>
    <h1>Search Study Groups</h1>
    <form method="get" action="search_study_groups.php">
        <label for="course_id">Filter by Course ID:</label>
        <input type="text" id="course_id" name="course_id" maxlength="10"><br><br>

        <label for="day">Filter by Day:</label>
        <select id="day" name="day">
            <option value="">Any</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select><br><br>

        <button type="submit">Search</button>
    </form>

    <hr>

    <?php
    // Only run search if parameters are present or form submitted
    if (isset($_GET['course_id']) || isset($_GET['day'])) {
        include 'db.php';

        $course_id = $_GET['course_id'];
        $day = $_GET['day'];

        echo "<h2>Results</h2>";

        // Build query
        $sql = "SELECT * FROM Study_Groups WHERE 1=1";

        if (!empty($course_id)) {
            $sql .= " AND course_id = '$course_id'";
        }
        if (!empty($day)) {
            $sql .= " AND day = '$day'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<thead>
                    <tr>
                        <th>Group ID</th>
                        <th>Description</th>
                        <th>Course ID</th>
                        <th>Location ID</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Current / Max</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['group_id'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['course_id'] . "</td>";
                echo "<td>" . $row['location_id'] . "</td>";
                echo "<td>" . $row['day'] . "</td>";
                echo "<td>" . $row['start_time'] . "</td>";
                echo "<td>" . $row['end_time'] . "</td>";
                echo "<td>" . $row['current_participants'] . " / " . $row['max_participants'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No study groups found matching your criteria.</p>";
        }
        $conn->close();
    }
    ?>

    <br>
    <a href="index.html">Back to Home</a>
</body>
</html>
